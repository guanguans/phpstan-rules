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
use Webmozart\Assert\Assert;

abstract class AbstractRuleTestCase extends RuleTestCase
{
    /**
     * @dataProvider provideRuleCases()
     *
     * @param array<int, list<int|string>> $expectedErrorMessagesWithLines
     *
     * @noinspection PhpUndefinedNamespaceInspection
     * @noinspection PhpLanguageLevelInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpUnitUndefinedDataProviderInspection
     * @noinspection PhpUnitTestsInspection
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideRuleCases')]
    final public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        Assert::allInteger(array_keys($expectedErrorMessagesWithLines));
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    final public function testRuleWithoutErrorMessage(): void
    {
        $this->analyse(glob(static::directory().'/Fixtures/Skip*.php'), []);
    }

    final public function testRuleCommon(): void
    {
        // self::assertInstanceOf(static::ruleClass(), $this->getRule());
        self::assertTrue(method_exists($this->getRule(), 'errorMessage'));
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

    protected static function invokeErrorMessage(...$args)
    {
        return static::invoke('errorMessage', ...$args);
    }

    protected static function invoke(string $method, ...$args)
    {
        $reflectionMethod = static::ruleReflectionClass()->getMethod($method);
        $reflectionMethod->setAccessible(true);

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
