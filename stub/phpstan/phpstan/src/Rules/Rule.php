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

namespace PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

if (interface_exists('PHPStan\Rules\Rule')) {
    return;
}

/**
 * @see https://github.com/Roave/BackwardCompatibilityCheck
 * @see https://github.com/symplify/phpstan-rules/blob/main/stubs/
 * @see vendor/phpstan/phpstan/phpstan.phar/src/Rules/Rule.php
 *
 * This is the interface custom rules implement. To register it in the configuration file
 * use the `phpstan.rules.rule` service tag:
 *
 * ```
 * services:
 * 	-
 *		class: App\MyRule
 *		tags:
 *			- phpstan.rules.rule
 * ```
 *
 * Learn more: https://phpstan.org/developing-extensions/rules
 *
 * @api
 *
 * @template TNodeType of Node
 */
interface Rule
{
    /**
     * @return class-string<TNodeType>
     */
    public function getNodeType(): string;

    /**
     * @param TNodeType $node
     * @param \PHPStan\Analyser\NodeCallbackInvoker&\PHPStan\Analyser\Scope $scope
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array;
}
