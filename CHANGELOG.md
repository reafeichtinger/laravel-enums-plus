# Changelog

All notable changes to `laravel-enums-plus` will be documented in this file.

## v2.7.0 - 2025-09-26

### What's Changed

* Initial release as Laravel Enums Plus.
* Add support for dynamic translations directly in the enum.
* Add support for pluralization and placeholders in all translations.

### New Contributors

* @reafeichtinger made their first contribution in https://github.com/reafeichtinger/laravel-enums-plus/commit/c6ad723bee4f9472fbbcb20bcd6ae09c3f2cb9d3

## v2.6.1 - 2025-03-05

### What's Changed

* ide.json: one code generation instead of two by @adelf in https://github.com/reafeichtinger/laravel-enums-plus/pull/45

### New Contributors

* @adelf made their first contribution in https://github.com/reafeichtinger/laravel-enums-plus/pull/45

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.6.0...v2.6.1

## v2.6.0 - 2025-03-02

### What's Changed

* Add Laravel Idea support. by @hailwood in https://github.com/reafeichtinger/laravel-enums-plus/pull/44

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.5.0...v2.6.0

## v2.5.0 - 2025-02-25

### What's Changed

* Laravel 12 Support by @hailwood in https://github.com/reafeichtinger/laravel-enums-plus/pull/43

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.4.0...v2.5.0

## v2.4.0 - 2025-01-21

### What's Changed

* Add make command by @Jim-Webfox in https://github.com/reafeichtinger/laravel-enums-plus/pull/40

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.3.1...v2.4.0

## v2.3.1 - 2024-03-15

### What's Changed

* Add unit/feature tests by @hailwood in https://github.com/reafeichtinger/laravel-enums-plus/pull/25
* Fix `::labels()` method by @hailwood in https://github.com/reafeichtinger/laravel-enums-plus/pull/25
* Add `AsFullEnumCollection::of(MyEnum::class)` for casts by @hailwood in https://github.com/reafeichtinger/laravel-enums-plus/pull/25
* Fix Issue #22: Replace 'self' with 'static'  by @vikas020807 in https://github.com/reafeichtinger/laravel-enums-plus/pull/24

### New Contributors

* @vikas020807 made their first contribution in https://github.com/reafeichtinger/laravel-enums-plus/pull/24

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.3.0...v2.3.1

## v2.3.0 - 2024-03-12

### What's Changed

* Bumping to version 11 of the Laravel framework (ahead of launch tomorrow) by @csoutham in https://github.com/reafeichtinger/laravel-enums-plus/pull/23
* Bump stefanzweifel/git-auto-commit-action from 4 to 5 by @dependabot in https://github.com/reafeichtinger/laravel-enums-plus/pull/19

### New Contributors

* @csoutham made their first contribution in https://github.com/reafeichtinger/laravel-enums-plus/pull/23

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.2.0...v2.3.0

## v2.2.0 - 2023-10-07

### What's Changed

- Add EnumCollection support by @hailwood in https://github.com/reafeichtinger/laravel-enums-plus/pull/17
  Fix toJson method to actually return a json string instead of an array.
  Add new `AsFullEnumCollection` cast - See the readme for usage and a description of why this is useful.

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.1.1...v2.2.0

## v2.1.1 - 2023-10-04

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v2.1.0...v2.1.1

## v2.1.0 - 2023-10-05

- Add static `rule()` method as a shortcut for the laravel validation rule.

## v2.0.0 - 2023-08-28

### What's Changed

- Use value in `map()` method

## v1.2.3 - 2023-03-02

### What's Changed

- Add additional comparison methods

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v1.1.0...v1.2.3

## v1.2.2 - 2023-02-28

### What's Changed

- Add support for direct value comparisons

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v1.2.1...v1.2.2

## v1.2.1 - 2023-02-22

### What's Changed

- Add support for laravel 10

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v1.1.0...v1.2.1

## v1.1.0 - 2022-10-04

### What's Changed

- Add support for rendering enums in blade templates

**Full Changelog**: https://github.com/reafeichtinger/laravel-enums-plus/compare/v1.0.1...v1.1.0

## Added new fields to toArray, minor bug fixes - 2022-09-21

Added new fields to toArray, minor bug fixes

## v1.0.0 - 2022-09-19

Initial release
