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

namespace Guanguans\PHPStanRules\Rule\Class_;

use Guanguans\PHPStanRules\Rule\AbstractRule;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @see \Guanguans\PHPStanRulesTests\Rule\Class_\ExceptionMustImplementNativeThrowableRule\ExceptionMustImplementNativeThrowableRuleTest
 * @see \Guanguans\RectorRules\Rector\New_\NewExceptionToNewAnonymousExtendsExceptionImplementsRector
 * @see https://github.com/symfony/ai/blob/main/.phpstan/ForbidNativeExceptionRule.php
 * @see https://github.com/thecodingmachine/phpstan-strict-rules/tree/master/src/Rules/Exceptions/
 *
 * @extends AbstractRule<Class_|New_>
 */
final class ExceptionMustImplementNativeThrowableRule extends AbstractRule
{
    private string $nativeThrowable;

    /**
     * @param class-string<\Throwable> $nativeThrowable
     */
    public function __construct(string $nativeThrowable)
    {
        \assert(is_subclass_of($nativeThrowable, \Throwable::class));
        $this->nativeThrowable = $nativeThrowable;
    }

    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @param \PhpParser\Node\Expr\New_|\PhpParser\Node\Stmt\Class_ $node
     *
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var list<null|Identifier|Name> $classNameNodes */
        $classNameNodes = [];

        if ($node instanceof Class_) {
            $classNameNodes = array_merge($classNameNodes, $this->resolveClassNameNodes($node, $scope));
        }

        if ($node instanceof New_ && $node->class instanceof Name) {
            $classNameNodes = array_merge($classNameNodes, [$node->class]);
        }

        if ([] === $classNameNodes) {
            return [];
        }

        return array_reduce(
            array_filter($classNameNodes),
            /**
             * @param \PhpParser\Node\Identifier|\PhpParser\Node\Name $classNameNode
             *
             * @throws \PHPStan\ShouldNotHappenException
             */
            function (array $carry, $classNameNode) use ($scope): array {
                $className = $this->resolveClassName($classNameNode, $scope);

                if (is_subclass_of($className, \Throwable::class) && !is_a($className, $this->nativeThrowable, true)) {
                    $carry[] = RuleErrorBuilder::message($this->errorMessage($className))
                        ->identifier($this->identifier())
                        ->line($classNameNode->getStartLine())
                        ->build();
                }

                return $carry;
            },
            []
        );
    }

    /**
     * @return list<null|Identifier|Name>
     */
    private function resolveClassNameNodes(Class_ $classNode, Scope $scope): array
    {
        if ($classNode->name instanceof Identifier && !$classNode->isAnonymous()) {
            return [$classNode->name];
        }

        // Anonymous class
        foreach ($classNode->implements as $implementNameNode) {
            $className = $this->resolveClassName($implementNameNode, $scope);

            if (is_subclass_of($className, \Throwable::class)) {
                return [$implementNameNode];
            }
        }

        return [$classNode->extends];
    }

    /**
     * @param \PhpParser\Node\Identifier|\PhpParser\Node\Name $classNameNode
     */
    private function resolveClassName($classNameNode, Scope $scope): string
    {
        // $className = $classNameNode instanceof Name ? $scope->resolveName($classNameNode) : $classNameNode->toString();
        $className = $classNameNode->toString();
        $classNameNode instanceof FullyQualified or $className = "{$scope->getNamespace()}\\$className";

        return $className;
    }

    private function errorMessage(string $class): string
    {
        return \sprintf(
            'The exception [%s] must implement the native throwable [%s].',
            $class,
            $this->nativeThrowable
        );
    }
}
