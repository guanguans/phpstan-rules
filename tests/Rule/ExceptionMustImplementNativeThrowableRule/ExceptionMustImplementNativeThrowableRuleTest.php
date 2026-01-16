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

namespace Guanguans\PHPStanRulesTests\Rule\ExceptionMustImplementNativeThrowableRule;

use Guanguans\PHPStanRulesTests\Rule\AbstractRuleTestCase;

final class ExceptionMustImplementNativeThrowableRuleTest extends AbstractRuleTestCase
{
    /**
     * @return \Iterator<array<array<int, mixed>, mixed>>
     */
    public static function provideRuleCases(): iterable
    {
        $errorMessage = self::invokeErrorMessage(\Exception::class);

        yield [__DIR__.'/Fixtures/ExceptionMustImplementNativeThrowable.php', [[$errorMessage, 21]]];
    }

    protected static function directory(): string
    {
        return __DIR__;
    }
}
