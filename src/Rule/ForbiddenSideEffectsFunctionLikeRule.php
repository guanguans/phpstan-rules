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
use PhpParser\Node\FunctionLike;
use PHPStan\Analyser\Scope;
use PHPStan\File\FileReader;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use staabm\SideEffectsDetector\SideEffectsDetector;

/**
 * @see https://github.com/staabm/side-effects-detector
 * @see \Guanguans\PHPStanRulesTests\Rule\ForbiddenSideEffectsFunctionLikeRule\ForbiddenSideEffectsFunctionLikeRuleTest
 *
 * @implements Rule<\PhpParser\Node\FunctionLike>
 */
final class ForbiddenSideEffectsFunctionLikeRule implements Rule
{
    /** @api */
    public const ERROR_MESSAGE = 'The function like contains side effects: [%s].';
    private SideEffectsDetector $sideEffectsDetector;

    public function __construct(SideEffectsDetector $sideEffectsDetector)
    {
        $this->sideEffectsDetector = $sideEffectsDetector;
    }

    public function getNodeType(): string
    {
        return FunctionLike::class;
    }

    /**
     * @param \PhpParser\Node\FunctionLike $node
     *
     * @throws \PHPStan\File\CouldNotReadFileException
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $sideEffects = $this->sideEffectsDetector->getSideEffects((string) substr(
            FileReader::read($scope->getFile()),
            $node->getStartFilePos(),
            $node->getEndFilePos() - $node->getStartFilePos() + 1
        ));

        $sideEffects = collect($sideEffects)
            // ->dump()
            ->reject(static fn (string $sideEffect): bool => \in_array(
                $sideEffect,
                ['standard_output', 'standard_output'],
                true
            ))
            // ->dump()
            ->all();

        if ([] === $sideEffects) {
            return [];
        }

        return [
            RuleErrorBuilder::message($this->createErrorMessage($sideEffects))
                ->identifier('guanguans.forbiddenSideEffectsFunctionLike')
                ->build(),
        ];
    }

    /**
     * @param list<string> $sideEffects
     */
    private function createErrorMessage(array $sideEffects): string
    {
        return \sprintf(self::ERROR_MESSAGE, implode(', ', $sideEffects));
    }
}
