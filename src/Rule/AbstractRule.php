<?php

declare(strict_types=1);

/**
 * Copyright (c) 2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/phpstan-rules
 */

namespace Guanguans\PHPStanRules\Rule;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @see https://github.com/phpstan/phpstan-strict-rules
 * @see https://github.com/symplify/phpstan-rules
 * @see https://github.com/ergebnis/phpstan-rules
 *
 * @template TNodeType of Node
 *
 * @implements Rule<TNodeType>
 */
abstract class AbstractRule implements Rule
{
    final protected function identifier(): string
    {
        return (string) Str::of(static::class)
            ->afterLast('\\')
            ->beforeLast('Rule')
            // ->lcfirst()
            ->pipe(static fn (Stringable $stringable): string => lcfirst((string) $stringable))
            ->prepend('guanguans', '.');
    }
}
