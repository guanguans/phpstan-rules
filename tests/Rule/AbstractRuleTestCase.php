<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpVoidFunctionResultUsedInspection */
/** @noinspection StaticClosureCanBeUsedInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/phpstan-rules
 */

namespace Guanguans\PHPStanRulesTests\Rule;

use Guanguans\PHPStanRules\Rule\AbstractRule;
use Illuminate\Support\Str;
use PHPStan\Testing\RuleTestCase;

abstract class AbstractRuleTestCase extends RuleTestCase
{
    private const ERROR_MESSAGE_METHOD_NAME = 'errorMessage';

    /**
     * @dataProvider provideRuleCases()
     *
     * @param list<array{0: string, 1: int, 2?: null|string}> $expectedErrorMessages
     *
     * @noinspection PhpUndefinedNamespaceInspection
     * @noinspection PhpLanguageLevelInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpUnitUndefinedDataProviderInspection
     * @noinspection PhpUnitTestsInspection
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideRuleCases')]
    final public function testRule(string $filePath, array $expectedErrorMessages): void
    {
        $this->analyse([$filePath], $expectedErrorMessages);
    }

    final public function testRuleWithoutErrorMessage(): void
    {
        $this->analyse(glob(static::directory().'/Fixtures/Skip*.php'), []);
    }

    final public function testRuleConstructor(): void
    {
        self::assertInstanceOf(
            static::ruleClass(),
            static::ruleReflectionClass()->newInstanceArgs(static::ruleParameters())
        );
    }

    final public function testRuleBasicInformation(): void
    {
        self::assertTrue(is_subclass_of(static::ruleClass(), AbstractRule::class));
        self::assertTrue(method_exists($this->getRule(), self::ERROR_MESSAGE_METHOD_NAME));
        self::assertFileExists(\sprintf(
            '%s/Fixtures/%s.php',
            static::directory(),
            Str::beforeLast(static::ruleReflectionClass()->getShortName(), 'Rule')
        ));
    }

    /**
     * @return list<string>
     */
    final public static function getAdditionalConfigFiles(): array
    {
        return [static::directory().'/config/configured_rule.neon'];
    }

    abstract protected static function directory(): string;

    protected function getRule(): AbstractRule
    {
        return static::rawGetRule();
    }

    /**
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    protected static function rawGetRule(): AbstractRule
    {
        return static::getContainer()->getByType(static::ruleClass());
    }

    /**
     * @throws \PHPStan\DependencyInjection\MissingServiceException
     * @throws \PHPStan\DependencyInjection\ParameterNotFoundException
     * @throws \ReflectionException
     *
     * @return array<string, mixed>
     */
    protected static function ruleParameters(): array
    {
        [$namespace, $name] = explode('.', static::invokeRuleMethod('identifier'), 2);
        $rawParameters = static::getContainer()->getParameter($namespace)[$name];

        return array_reduce(
            static::ruleReflectionClass()->getConstructor()->getParameters(),
            function (array $parameters, \ReflectionParameter $reflectionParameter) use ($rawParameters): array {
                $parameterName = $reflectionParameter->getName();

                if (class_exists($typeName = $reflectionParameter->getType()->getName())) {
                    $parameters[$parameterName] = static::getContainer()->getByType($typeName);
                } elseif (isset($rawParameters[$parameterName])) {
                    $parameters[$parameterName] = $rawParameters[$parameterName];
                } elseif ($reflectionParameter->isDefaultValueAvailable()) {
                    $parameters[$parameterName] = $reflectionParameter->getDefaultValue();
                }

                return $parameters;
            },
            []
        );
    }

    protected static function invokeRuleErrorMessageMethod(...$args)
    {
        return static::invokeRuleMethod(self::ERROR_MESSAGE_METHOD_NAME, ...$args);
    }

    protected static function invokeRuleMethod(string $method, ...$args)
    {
        $reflectionMethod = static::ruleReflectionClass()->getMethod($method);
        \PHP_VERSION_ID < 80100 and $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke(static::rawGetRule(), ...$args);
    }

    /**
     * @throws \ReflectionException
     *
     * @return \ReflectionClass<\Guanguans\PHPStanRules\Rule\AbstractRule>
     */
    protected static function ruleReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(static::ruleClass());
    }

    /**
     * @return class-string<\Guanguans\PHPStanRules\Rule\AbstractRule>
     */
    protected static function ruleClass(): string
    {
        return (string) Str::of((new \ReflectionClass(static::class))->getNamespaceName())->replace(
            'PHPStanRulesTests',
            'PHPStanRules'
        );
    }
}
