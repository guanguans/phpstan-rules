<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

<a name="unreleased"></a>
## [Unreleased]


<a name="1.1.1"></a>
## [1.1.1] - 2026-03-25
### 📦 Builds
- **dependencies:** Add new Pest and Rector plugins ([be73d14](https://github.com/guanguans/phpstan-rules/commit/be73d14))

### 🤖 Continuous Integrations
- **config:** Update github config files ([fd18c2d](https://github.com/guanguans/phpstan-rules/commit/fd18c2d))
- **config:** Update config files ([ee3b00e](https://github.com/guanguans/phpstan-rules/commit/ee3b00e))


<a name="1.1.0"></a>
## [1.1.0] - 2026-03-19
### 🐞 Bug Fixes
- **composer.json:** Update dependencies to latest versions ([5583df4](https://github.com/guanguans/phpstan-rules/commit/5583df4))

### 📦 Builds
- **dependencies:** Update illuminate/support and dev dependencies ([1e5128f](https://github.com/guanguans/phpstan-rules/commit/1e5128f))
- **deps-dev:** update shipmonk/dead-code-detector requirement || ^0.15 ([c14fbea](https://github.com/guanguans/phpstan-rules/commit/c14fbea))

### Pull Requests
- Merge pull request [#2](https://github.com/guanguans/phpstan-rules/issues/2) from guanguans/dependabot/composer/shipmonk/dead-code-detector-tw-0.14or-tw-0.15


<a name="1.0.3"></a>
## [1.0.3] - 2026-02-05
### 🐞 Bug Fixes
- **ExceptionMustImplementNativeThrowableRule:** Improve type assertion for nativeThrowable ([17d8273](https://github.com/guanguans/phpstan-rules/commit/17d8273))

### 💅 Code Refactorings
- apply rector ([eaa34e0](https://github.com/guanguans/phpstan-rules/commit/eaa34e0))

### ✅ Tests
- **tests:** Add test for rule class name consistency ([9048389](https://github.com/guanguans/phpstan-rules/commit/9048389))

### 📦 Builds
- **dependencies:** Update composer dependencies to latest versions ([30334a2](https://github.com/guanguans/phpstan-rules/commit/30334a2))


<a name="1.0.2"></a>
## [1.0.2] - 2026-01-19
### ✨ Features
- **rules:** Add configuration for ForbiddenSideEffectsRule ([08bf5a3](https://github.com/guanguans/phpstan-rules/commit/08bf5a3))

### 🐞 Bug Fixes
- Add classmap autoloading and create Rule interface for PHPStan ([3ad2c55](https://github.com/guanguans/phpstan-rules/commit/3ad2c55))


<a name="1.0.1"></a>
## [1.0.1] - 2026-01-18
### ✨ Features
- **rules:** Add AbstractMixedTypeRule for node processing ([8372569](https://github.com/guanguans/phpstan-rules/commit/8372569))
- **rules:** Add PHPStan configuration for neon file handling ([96ef5b1](https://github.com/guanguans/phpstan-rules/commit/96ef5b1))
- **rules:** Enhance ForbiddenSideEffectsFunctionLikeRule for file nodes ([4fa8bc9](https://github.com/guanguans/phpstan-rules/commit/4fa8bc9))

### 💅 Code Refactorings
- **rules:** Rename AbstractMixedTypeRule to AbstractMixedNodeTypeRule ([6660d82](https://github.com/guanguans/phpstan-rules/commit/6660d82))
- **rules:** Rename ForbiddenSideEffectsFunctionLikeRule to ForbiddenSideEffectsRule ([87fa6a9](https://github.com/guanguans/phpstan-rules/commit/87fa6a9))


<a name="1.0.0"></a>
## 1.0.0 - 2026-01-15
### ✨ Features
- **rules:** Add ForbiddenSideEffectsCodeRule for side-effect detection ([962c34b](https://github.com/guanguans/phpstan-rules/commit/962c34b))
- **rules:** Add ExceptionMustImplementNativeThrowableRule ([d5e5e38](https://github.com/guanguans/phpstan-rules/commit/d5e5e38))

### 💅 Code Refactorings
- **rules:** Rename 'implement' to 'nativeThrowable' ([86a52b1](https://github.com/guanguans/phpstan-rules/commit/86a52b1))

### 📦 Builds
- **dependencies:** Add new PHPStan rules and no-floaters package ([c702a5b](https://github.com/guanguans/phpstan-rules/commit/c702a5b))


[Unreleased]: https://github.com/guanguans/phpstan-rules/compare/1.1.1...HEAD
[1.1.1]: https://github.com/guanguans/phpstan-rules/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/guanguans/phpstan-rules/compare/1.0.3...1.1.0
[1.0.3]: https://github.com/guanguans/phpstan-rules/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/guanguans/phpstan-rules/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/guanguans/phpstan-rules/compare/1.0.0...1.0.1
