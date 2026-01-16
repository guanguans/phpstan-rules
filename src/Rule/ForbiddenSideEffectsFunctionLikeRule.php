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
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\Scope;
use PHPStan\File\FileReader;
use PHPStan\Node\FileNode;
use PHPStan\Rules\RuleErrorBuilder;
use staabm\SideEffectsDetector\SideEffectsDetector;

/**
 * @see \Guanguans\PHPStanRulesTests\Rule\ForbiddenSideEffectsFunctionLikeRule\ForbiddenSideEffectsFunctionLikeRuleTest
 * @see https://github.com/staabm/side-effects-detector
 *
 * @extends AbstractRule<FileNode>
 */
final class ForbiddenSideEffectsFunctionLikeRule extends AbstractRule
{
    private const IGNORED_SIDE_EFFECTS = [
        'maybe_has_side_effects',
        'scope_pollution',
    ];
    private SideEffectsDetector $sideEffectsDetector;

    public function __construct(SideEffectsDetector $sideEffectsDetector)
    {
        $this->sideEffectsDetector = $sideEffectsDetector;
    }

    public function getNodeType(): string
    {
        return FileNode::class;
    }

    /**
     * @param \PHPStan\Node\FileNode $node
     *
     * @throws \PHPStan\File\CouldNotReadFileException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $contents = FileReader::read($scope->getFile());

        return collect($node->getNodes())
            ->map(fn (Node $node): array => $this->rawProcessNode($node, $contents))
            ->flatten()
            ->all();
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<\PHPStan\Rules\RuleError>
     */
    private function rawProcessNode(Node $node, string $contents): array
    {
        if (property_exists($node, 'stmts') && \is_array($node->stmts)) {
            return array_map(
                fn (Stmt $stmtNode): array => $this->rawProcessNode($stmtNode, $contents),
                $node->stmts
            );
        }

        $sideEffects = array_filter(
            $this->sideEffectsDetector->getSideEffects($this->parseNodeCode($node, $contents)),
            static fn (string $sideEffect): bool => !\in_array($sideEffect, self::IGNORED_SIDE_EFFECTS, true)
        );

        return [] === $sideEffects
            ? []
            : [
                RuleErrorBuilder::message($this->errorMessage($sideEffects))
                    ->identifier($this->identifier())
                    ->line($node->getStartLine())
                    ->build(),
            ];
    }

    private function parseNodeCode(Node $node, string $contents): string
    {
        return Str::start(
            (string) substr(
                $contents,
                $node->getStartFilePos(),
                $node->getEndFilePos() - $node->getStartFilePos() + 1
            ),
            '<?php '
        );
    }

    /**
     * @param list<string> $sideEffects
     */
    private function errorMessage(array $sideEffects): string
    {
        return \sprintf(
            'The function like contains side effects: [%s].',
            implode(', ', $sideEffects)
        );
    }
}
