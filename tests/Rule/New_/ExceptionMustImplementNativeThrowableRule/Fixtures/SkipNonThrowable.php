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

namespace Guanguans\PHPStanRulesTests\Rule\New_\ExceptionMustImplementNativeThrowableRule\Fixtures;

final class SkipImplemented
{
    public function run(): void
    {
        new \stdClass;
    }
}
