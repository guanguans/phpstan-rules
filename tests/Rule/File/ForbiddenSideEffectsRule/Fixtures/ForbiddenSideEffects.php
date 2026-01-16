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

namespace Guanguans\PHPStanRulesTests\Rule\File\ForbiddenSideEffectsRule\Fixtures;

final class ForbiddenSideEffects
{
    public function run(): void
    {
        require __FILE__;
        unknown_function();
        echo __METHOD__;

        exit(0);
    }
}
