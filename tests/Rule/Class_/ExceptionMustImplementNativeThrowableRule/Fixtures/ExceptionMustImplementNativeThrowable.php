<?php

/** @noinspection ALL */
declare(strict_types=1);

/**
 * Copyright (c) 2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/phpstan-rules
 */

namespace Guanguans\PHPStanRulesTests\Rule\Class_\ExceptionMustImplementNativeThrowableRule\Fixtures;

final class ExceptionMustImplementNativeThrowable
{
    public function run(): void
    {
        new \RuntimeException(fake()->text());
        new NonImplementedNativeThrowableException(fake()->text());
        new class(fake()->text()) extends NonImplementedNativeThrowableException implements \Throwable {};
        new class(fake()->text()) extends NonImplementedNativeThrowableException {};
    }
}
