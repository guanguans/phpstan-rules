# phpstan-rules

> [!NOTE]
> A set of additional rules for phpstan/phpstan. - 一套针对 `phpstan/phpstan` 的附加规则。

[![tests](https://github.com/guanguans/phpstan-rules/actions/workflows/tests.yml/badge.svg)](https://github.com/guanguans/phpstan-rules/actions/workflows/tests.yml)
[![php-cs-fixer](https://github.com/guanguans/phpstan-rules/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/guanguans/phpstan-rules/actions/workflows/php-cs-fixer.yml)
[![codecov](https://codecov.io/gh/guanguans/phpstan-rules/graph/badge.svg?token=0RtgSGom4K)](https://codecov.io/gh/guanguans/phpstan-rules)
[![Latest Stable Version](https://poser.pugx.org/guanguans/phpstan-rules/v)](https://packagist.org/packages/guanguans/phpstan-rules)
[![GitHub release (with filter)](https://img.shields.io/github/v/release/guanguans/phpstan-rules)](https://github.com/guanguans/phpstan-rules/releases)
[![Total Downloads](https://poser.pugx.org/guanguans/phpstan-rules/downloads)](https://packagist.org/packages/guanguans/phpstan-rules)
[![License](https://poser.pugx.org/guanguans/phpstan-rules/license)](https://packagist.org/packages/guanguans/phpstan-rules)

## Requirement

* PHP >= 7.4

## Installation

```shell
composer require guanguans/phpstan-rules --dev --ansi -v
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
<summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include rules.neon in your project's PHPStan config:

```neon
includes:
    - vendor/guanguans/phpstan-rules/config/rules.neon
```
</details>

## Usage

Parameter configuration refer to the parameter section the configuration file [[config/rules.neon](config/rules.neon)].

You can also refer to the configuration file [tests/Rule/.../.../config/configured_rule.neon] in the tests directory.

## Composer scripts

```shell
composer checks:required
composer php-cs-fixer:fix
composer phpstan-rules:fix-neon-files
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

* [guanguans](https://github.com/guanguans)
* [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
