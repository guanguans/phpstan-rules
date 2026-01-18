<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

/**
 * Copyright (c) 2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/phpstan-rules
 */

namespace Guanguans\PHPStanRules\Support;

use Composer\Script\Event;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @internal
 *
 * @property \Symfony\Component\Console\Output\ConsoleOutput $output
 *
 * @method void configureIO(InputInterface $input, OutputInterface $output)
 */
final class ComposerScripts
{
    /**
     * @see \PhpCsFixer\Hasher
     * @see \PhpCsFixer\Utils
     */
    private function __construct() {}

    /**
     * @throws \ErrorException*@see vendor/phpstan/phpstan/phpstan.phar/preload.php
     * @throws \ReflectionException
     *
     * @return int<0>|never-returns<1>
     *
     * @noinspection PhpDocSignatureInspection
     */
    public static function listFiles(Event $event): int
    {
        self::requireAutoload($event);

        // require_once 'phar://vendor/phpstan/phpstan/phpstan.phar/preload.php';
        require_once 'phar://vendor/phpstan/phpstan/phpstan.phar/vendor/autoload.php';

        classes(
            static fn (string $class, string $file): bool => Str::of($class)->startsWith('PHPStan\\')
                && Str::of($class)->endsWith([
                    'er',
                    // 'Renamer',
                    // 'Resolver',
                    'Factory',
                ])
                && !Str::of($file)->contains([
                    '/src/Testing/PHPUnit/InitContainerBeforeDataProviderSubscriber.php',
                    '/src/Testing/PHPUnit/InitContainerBeforeTestSubscriber.php',
                    '/phpstan-deprecation-rules/',
                    '/phpstan-strict-rules/',
                ])
        )
            ->sortKeys()
            // ->groupBy(fn (string $class) => str($class)->explode('\\')->get(1))
            // ->keys()
            ->tap(static function (Collection $classes) use ($event): void {
                $event->getIO()->info('');
                $event->getIO()->info("Found {$classes->count()} files:");
                $event->getIO()->info('');
            })
            ->each(static function ($reflectionClass) use ($event): void {
                $event->getIO()->info(Str::remove(getcwd().\DIRECTORY_SEPARATOR, $reflectionClass->getFileName()));
            });

        $event->getIO()->info('');
        $event->getIO()->info('No errors');

        return 0;
    }

    public static function fixNeonFiles(Event $event): int
    {
        self::requireAutoload($event);

        collect(
            Finder::create()
                ->in(getcwd())
                ->name(['*.neon', '*.neon.dist'])
                ->ignoreDotFiles(false)
                ->ignoreUnreadableDirs(false)
                ->ignoreVCS(true)
                ->ignoreVCSIgnored(true)
                ->files()
        )
            ->tap(static function (Collection $files) use ($event): void {
                $event->getIO()->info('');
                $event->getIO()->info("Found {$files->count()} neon files:");
                $event->getIO()->info('');
            })
            ->each(static function (SplFileInfo $file) use ($event): void {
                $event->getIO()->info(Str::remove(getcwd().\DIRECTORY_SEPARATOR, $file->getRealPath()));
            })
            ->reduce(
                static function (Collection $carry, SplFileInfo $file): Collection {
                    $contents = $file->getContents();
                    $fixedContents = (string) Str::of($contents)->replace("\t", '    ')->trim()->append("\n");

                    if ($contents === $fixedContents) {
                        return $carry;
                    }

                    file_put_contents($file->getRealPath(), $fixedContents);

                    return $carry->push($file);
                },
                collect()
            )
            ->tap(static function (Collection $fixedFiles) use ($event): void {
                $event->getIO()->info('');
                $event->getIO()->info("Fixed {$fixedFiles->count()} neon files:");
                $event->getIO()->info('');
            })
            ->each(static function (SplFileInfo $file) use ($event): void {
                $event->getIO()->info(Str::remove(getcwd().\DIRECTORY_SEPARATOR, $file->getRealPath()));
            })
            ->tap(static function (Collection $fixedFiles): void {
                $fixedFiles->isEmpty() or exit(1);
            });

        $event->getIO()->info('No errors');

        return 0;
    }

    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public static function requireAutoload(Event $event, ?bool $enableDebugging = null): void
    {
        $enableDebugging ??= (new ArgvInput)->hasParameterOption('-vvv', true);
        $enableDebugging and $event->getIO()->enableDebugging(microtime(true));
        (fn () => $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG))->call($event->getIO());

        require_once $event->getComposer()->getConfig()->get('vendor-dir').\DIRECTORY_SEPARATOR.'autoload.php';
    }

    public static function makeArgvInput(?array $argv = null, ?InputDefinition $inputDefinition = null): ArgvInput
    {
        static $argvInput;

        return $argvInput ??= new ArgvInput($argv, $inputDefinition);
    }

    /**
     * @see \Rector\Console\Style\SymfonyStyleFactory
     */
    public static function makeSymfonyStyle(?InputInterface $input = null, ?OutputInterface $output = null): SymfonyStyle
    {
        static $symfonyStyle;

        if (
            $symfonyStyle instanceof SymfonyStyle
            && (
                !$input instanceof InputInterface
                || (string) \Closure::bind(
                    static fn (SymfonyStyle $symfonyStyle): InputInterface => $symfonyStyle->input,
                    null,
                    SymfonyStyle::class
                )($symfonyStyle) === (string) $input
            )
            && (
                !$output instanceof OutputInterface
                || \Closure::bind(
                    static fn (SymfonyStyle $symfonyStyle): OutputInterface => $symfonyStyle->output,
                    null,
                    SymfonyStyle::class
                )($symfonyStyle) === $output
            )
        ) {
            return $symfonyStyle;
        }

        $input ??= new ArgvInput;
        $output ??= new ConsoleOutput;

        // to configure all -v, -vv, -vvv options without memory-lock to Application run() arguments
        (fn () => $this->configureIO($input, $output))->call(new Application);

        // --debug or --xdebug is called
        if ($input->hasParameterOption(['--debug', '--xdebug'], true)) {
            $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        }

        // disable output for testing
        if (self::isRunningInTesting()) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }

        return $symfonyStyle = new SymfonyStyle($input, $output);
    }

    public static function isRunningInTesting(): bool
    {
        return 'testing' === getenv('ENV');
    }
}
