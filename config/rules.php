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

use Guanguans\PHPStanRules\Rule\File\ForbiddenSideEffectsRule;
use staabm\SideEffectsDetector\SideEffectsDetector;

if (!class_exists(SideEffectsDetector::class)) {
    return [];
}

return [
    'conditionalTags' => [
        ForbiddenSideEffectsRule::class => [
            'phpstan.rules.rule' => '%guanguans.forbiddenSideEffects.enabled%',
        ],
    ],
    'services' => [
        [
            'class' => SideEffectsDetector::class,
        ],
        [
            'class' => ForbiddenSideEffectsRule::class,
        ],
    ],
];
