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

namespace Guanguans\PHPStanRules\Support;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;

if (!\function_exists('Guanguans\PHPStanRules\Support\clone_node')) {
    /**
     * @see \DeepCopy\deep_copy()
     *
     * @template TNode of Node
     *
     * @param TNode $node
     *
     * @return TNode
     */
    function clone_node(Node $node): Node
    {
        /** @var array{0: TNode} $nodes */
        $nodes = (new NodeTraverser(new CloningVisitor))->traverse([$node]);

        return $nodes[0];
    }
}

if (!\function_exists('Guanguans\PHPStanRules\Support\is_class_of_all')) {
    /**
     * @param mixed $objectOrClass
     * @param list<class-string> $classes
     *
     * @noinspection CallableParameterUseCaseInTypeContextInspection
     */
    function is_class_of_all($objectOrClass, array $classes, ?bool $allowString = null): bool
    {
        $allowString ??= \is_string($objectOrClass);

        foreach ($classes as $class) {
            if (!is_a($objectOrClass, $class, $allowString)) {
                return false;
            }
        }

        return true;
    }
}

if (!\function_exists('Guanguans\PHPStanRules\Support\is_class_of_any')) {
    /**
     * @see is_a()
     * @see \Webmozart\Assert\Assert::isAnyOf()
     * @see \Webmozart\Assert\Assert::isAOf()
     *
     * @param mixed $objectOrClass
     * @param list<class-string> $classes
     *
     * @noinspection CallableParameterUseCaseInTypeContextInspection
     */
    function is_class_of_any($objectOrClass, array $classes, ?bool $allowString = null): bool
    {
        $allowString ??= \is_string($objectOrClass);

        foreach ($classes as $class) {
            if (is_a($objectOrClass, $class, $allowString)) {
                return true;
            }
        }

        return false;
    }
}

if (!\function_exists('Guanguans\PHPStanRules\Support\is_instance_of_all')) {
    /**
     * @param mixed $objectOrClass
     * @param list<class-string> $classes
     */
    function is_instance_of_all($objectOrClass, array $classes): bool
    {
        foreach ($classes as $class) {
            if (!$objectOrClass instanceof $class) {
                return false;
            }
        }

        return true;
    }
}

if (!\function_exists('Guanguans\PHPStanRules\Support\is_instance_of_any')) {
    /**
     * @see array_all()
     * @see array_any()
     * @see \Webmozart\Assert\Assert::allIsInstanceOf()
     * @see \Webmozart\Assert\Assert::allIsInstanceOfAny()
     * @see \Webmozart\Assert\Assert::isInstanceOf()
     * @see \Webmozart\Assert\Assert::isInstanceOfAny()
     *
     * @param mixed $objectOrClass
     * @param list<class-string> $classes
     */
    function is_instance_of_any($objectOrClass, array $classes): bool
    {
        foreach ($classes as $class) {
            if ($objectOrClass instanceof $class) {
                return true;
            }
        }

        return false;
    }
}

if (!\function_exists('Guanguans\PHPStanRules\Support\is_subclass_of_all')) {
    /**
     * @param mixed $objectOrClass
     * @param list<class-string> $classes
     */
    function is_subclass_of_all($objectOrClass, array $classes, ?bool $allowString = null): bool
    {
        $allowString ??= \is_string($objectOrClass);

        foreach ($classes as $class) {
            if (!is_subclass_of($objectOrClass, $class, $allowString)) {
                return false;
            }
        }

        return true;
    }
}

if (!\function_exists('Guanguans\PHPStanRules\Support\is_subclass_of_any')) {
    /**
     * @see is_subclass_of()
     * @see \Webmozart\Assert\Assert::subclassOf()
     *
     * @param mixed $objectOrClass
     * @param list<class-string> $classes
     */
    function is_subclass_of_any($objectOrClass, array $classes, ?bool $allowString = null): bool
    {
        $allowString ??= \is_string($objectOrClass);

        foreach ($classes as $class) {
            if (is_subclass_of($objectOrClass, $class, $allowString)) {
                return true;
            }
        }

        return false;
    }
}
