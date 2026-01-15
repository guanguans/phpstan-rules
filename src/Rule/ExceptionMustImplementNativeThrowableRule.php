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
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @see \Guanguans\PHPStanRulesTests\Rule\ExceptionMustImplementNativeThrowableRule\ExceptionMustImplementNativeThrowableRuleTest
 * @see \Guanguans\RectorRules\Rector\New_\NewExceptionToNewAnonymousExtendsExceptionImplementsRector
 * @see https://github.com/symfony/ai/blob/main/.phpstan/ForbidNativeExceptionRule.php
 * @see https://github.com/thecodingmachine/phpstan-strict-rules/tree/master/src/Rules/Exceptions/
 *
 * @implements Rule<Node\Expr\New_>
 */
final class ExceptionMustImplementNativeThrowableRule implements Rule
{
    /** @api */
    public const ERROR_MESSAGE = 'The exception [%s] must implement the native throwable [%s].';
    private string $nativeThrowable;

    /**
     * @param class-string<\Throwable> $nativeThrowable
     */
    public function __construct(string $nativeThrowable)
    {
        $this->nativeThrowable = $nativeThrowable;
    }

    public function getNodeType(): string
    {
        return New_::class;
    }

    /**
     * @param \PhpParser\Node\Expr\New_ $node
     *
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (
            /** 暂不处理匿名类 `new class() extends Exception {}` 的情况. */
            !$node->class instanceof Name
            || !is_subclass_of($class = $node->class->toString(), \Throwable::class)
            || is_subclass_of($class, $this->nativeThrowable)
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message($this->createErrorMessage($class))
                ->identifier('guanguans.exceptionMustImplementNativeThrowable')
                ->build(),
        ];
    }

    private function createErrorMessage(string $class): string
    {
        return \sprintf(self::ERROR_MESSAGE, $class, $this->nativeThrowable);
    }
}
