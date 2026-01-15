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

namespace Guanguans\PHPStanRulesTests\Rule\ExceptionMustImplementNativeThrowableRule\Source;

use Guanguans\PHPStanRules\Contract\ThrowableContract;

class Exception extends \Exception implements ThrowableContract {}
