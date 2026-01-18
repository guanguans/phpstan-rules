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

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use function Guanguans\PHPStanRules\Support\is_instance_of_any;

/**
 * @template TNodeType of Node
 *
 * @extends AbstractRule<Node>
 */
abstract class AbstractMixedNodeTypeRule extends AbstractRule
{
    /**
     * @return class-string<TNodeType>
     */
    final public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @param TNodeType $node
     * @param \PHPStan\Analyser\NodeCallbackInvoker&\PHPStan\Analyser\Scope $scope
     *
     * @return list<IdentifierRuleError>
     */
    final public function processNode(Node $node, Scope $scope): array
    {
        return is_instance_of_any($node, $this->getNodeTypes()) ? $this->rawProcessNode($node, $scope) : [];
    }

    /**
     * @return list<class-string<TNodeType>>
     */
    abstract protected function getNodeTypes(): array;

    /**
     * @param TNodeType $node
     * @param \PHPStan\Analyser\NodeCallbackInvoker&\PHPStan\Analyser\Scope $scope
     *
     * @return list<IdentifierRuleError>
     */
    abstract protected function rawProcessNode(Node $node, Scope $scope): array;
}
