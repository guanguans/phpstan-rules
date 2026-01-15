<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpVoidFunctionResultUsedInspection */
/** @noinspection StaticClosureCanBeUsedInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/phpstan-rules
 */

namespace Guanguans\PHPStanRulesTests\Rule\ForbiddenSideEffectsFunctionLikeRule;

use Guanguans\PHPStanRules\Rule\ForbiddenSideEffectsFunctionLikeRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Webmozart\Assert\Assert;

final class ForbiddenSideEffectsFunctionLikeRuleTest extends RuleTestCase
{
    /**
     * @dataProvider provideRuleCases()
     *
     * @param array<int, list<int|string>> $expectedErrorMessagesWithLines
     *
     * @noinspection PhpUndefinedNamespaceInspection
     * @noinspection PhpLanguageLevelInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideRuleCases')]
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        Assert::allInteger(array_keys($expectedErrorMessagesWithLines));
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    /**
     * @return \Iterator<array<array<int, mixed>, mixed>>
     */
    public static function provideRuleCases(): iterable
    {
        // $errorMessage = \sprintf(
        //     ForbiddenSideEffectsFunctionLikeRule::ERROR_MESSAGE,
        //     implode(', ', [
        //         'standard_output',
        //     ])
        // );

        // yield [__DIR__.'/Fixtures/ForbiddenSideEffectsFunctionLike.php', [[$errorMessage, 19]]];
        yield [__DIR__.'/Fixtures/ForbiddenSideEffectsFunctionLike.php', []];

        yield [__DIR__.'/Fixtures/SkipNonSideEffects.php', []];
    }

    /**
     * @return list<string>
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__.'/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForbiddenSideEffectsFunctionLikeRule::class);
    }
}
