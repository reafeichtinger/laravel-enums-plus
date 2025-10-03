![Banner Image](https://banners.beyondco.de/Laravel%20Enums%20Plus.png?theme=dark&packageManager=composer+require&packageName=reafeichtinger%2Flaravel-enums-plus&pattern=topography&style=style_2&description=Supercharge+your+PHP+8.1%2B+enums+in+Laravel&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg&heights=auto)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/reafeichtinger/laravel-enums-plus.svg?style=flat)](https://packagist.org/packages/reafeichtinger/laravel-enums-plus)
[![Total Downloads](https://img.shields.io/packagist/dt/reafeichtinger/laravel-enums-plus.svg?style=flat)](https://packagist.org/packages/reafeichtinger/laravel-enums-plus)

This package supercharges your PHP 8.1+ enums with superpowers like localization support and fluent comparison methods.

# Installation

```bash
composer require reafeichtinger/laravel-enums-plus
```

## Publishing the config 

You might want to overwrite some default settings. You can pushlish the config for further editing with this command.

```bash
php artisan vendor:publish --tag=laravel-enums-plus-config
```

# Creating a new enum class with the `make:enum` Command

Creating a new Laravel Enum is easy with the make:enum command.

## Command:

```Bash
php artisan make:enum-plus {name} --string # or --int
```

### Arguments:

* `{name}`: The name of the enum class to be created (e.g., OrderStatus). The command will automatically append "Enum" to the name (e.g., OrderStatusEnum).
* `{type?}`: The underlying data type for the enum. Can be either --int --string or if not specified it will be a pure enum.
* `{--force}`: Overwrite the enum if it already exists.
  Example Usage:

To create an enum named OrderStatusEnum backed by integers:

``` Bash
php artisan make:enum OrderStatus --int
```

To create an enum named OrderStatusEnum backed by strings:

``` Bash
php artisan make:enum OrderStatus --string
```

To create a pure enum named OrderStatusEnum:

``` Bash
php artisan make:enum OrderStatus
```

This will generate an OrderStatusEnums in the `app/Enums` directory.

# Upgrading your existing enums

The enum you create must implement the `EnumPlus` interface and also use the `IsEnumPlus` trait.
The interface is required for Laravel to cast your enum correctly and the trait is what gives your enum its superpowers.

```php
use Rea\LaravelEnumsPlus\EnumPlus;
use Rea\LaravelEnumsPlus\IsEnumPlus;

enum VolumeUnitEnum: string implements EnumPlus // Add this
{
    use IsEnumPlus; // Add this

    case MILLIGRAMS = "milligrams";
    case GRAMS = "grams";
    case KILOGRAMS = "kilograms";
    case TONNE = "tonne";
}
```

# Enhance your enum class

Once you have your basic enum class you can enhance and customize it so it fits your project.

## Enum value labels (Localization)

All translations support pluralization as well as placeholders but in some cases you cannot specify the count or the placeholders. In order to have control over the default values in this case you may define a `defaultCount` and `defaultReplace` method.

```php
public function defaultCount(): int
{
    return 0; // Would be 1 by default
}

public function defaultReplace(): array
{
    return [
        'of' => "butter", // Would be an empty array by default
    ];
}
```

### Translations directly in the enum

You can define translations directly inside of the enum class by adding a `translation` method:

```php
public function translations(): array
{
    return [
        'en' => [
            self::GRAMS->value => ':count gram of :of|:count grams of :of',
            self::MILLIGRAMS->value => ':count milligram of :of|:count milligrams of :of',
            self::KILOGRAMS->value => ':count kilogram of :of|:count kilograms of :of',
            self::TONNE->value => ':count tonne of :of|:count tonnes of :of',
        ],
        'de' => [
            self::GRAMS->value => ':count Gram :Of|:count Gram :Of',
            self::MILLIGRAMS->value => ':count Milligram :Of|:count Milligram :Of',
            self::KILOGRAMS->value => ':count Kilogram :Of|:count Kilogram :Of',
            self::TONNE->value => ':count Tonne :Of|:count Tonnen :Of',
        ],
    ];
}
```

If you do not specify a `translations` method, it will fallback to the enums.php translation file.

### Using laravel translations

Create `enums.php` lang file and create labels for your enum values. 

> **Note:**
> The path to the enum translations can be customized via the config file.

```php
// resources/lang/en/enums.php

return [
     VolumeUnitEnum::class => [
        VolumeUnitEnum::MILLIGRAMS->value => "mg",
        VolumeUnitEnum::GRAMS->value      => "g",
        VolumeUnitEnum::KILOGRAMS->value  => "kg",
        VolumeUnitEnum::TONNE->value      => "t"
     ]
];
```

You may then access these localized values using the `->label()`, `->trans()`, `::labelFor()` or `::transFor()` methods.  
Additionally rendering the enum in a blade template will render the label.

```php
VolumeUnitEnum::MILLIGRAMS->label(); // "mg"
VolumeUnitEnum::MILLIGRAMS->trans(); // "mg"
VolumeUnitEnum::labelFor(VolumeUnitEnum::TONNE); // "t"
VolumeUnitEnum::transFor(VolumeUnitEnum::TONNE); // "t"
// in blade
{{ VolumeUnitEnum::KILOGRAMS }} // "kg" - Uses default count and replace values
```

If you do not specify a label in the lang file these methods will return the value assigned to the enum inside the enum file. e.g MILLIGRAMS label will be milligrams.

## Meta data

Adding metadata allows you to return additional values alongside the label and values.

Create a withMeta method on your enum to add metadata.

```php
public function withMeta(): array
{
    return match ($this) {
        self::MILLIGRAMS                => [
            'background_color' => 'bg-green-100',
            'text_color'       => 'text-green-800',
        ],
        self::GRAMS                     => [
            'background_color' => 'bg-red-100',
            'text_color'       => 'text-red-800',
        ],
        self::KILOGRAMS, self::TONNE    => [
            'background_color' => 'bg-gray-100',
            'text_color'       => 'text-gray-800',
        ],
        default                         => [
            'background_color' => 'bg-blue-100',
            'text_color'       => 'text-blue-800',
        ],
    };
}
```

If you do not specify a `withMeta` method, meta will be an empty array.

## Alias

Adding aliases allows you to match additional values to an enum case.

Create a withAlias method on your enum to add them.

```php
public function withAlias(): array
{
    return match ($this) {
        self::MILLIGRAMS    => ['mg'],
        self::GRAMS         => ['g'],
        self::KILOGRAMS     => ['kg'],
        self::TONNE         => ['t', 'ton'],
        default             => [],
    };
}
```

If you do not specify a `withAlias` method, meta will be an empty array.

# Other methods

## `options()`

Returns an array of all enum values with their labels and metadata.

### Usage

```php
VolumeUnitEnum::options();
VolumeUnitEnum::optionsC(); // For collection
```

returns

```php
[
    [
        'name'  => 'MILLIGRAMS',
        'value' => 'milligrams',
        'label' => 'mg',
        'meta'  => [
            'background_color' => 'bg-green-100',
            'text_color'       => 'text-green-800',
        ],
        'alias' => [
            'mg',
        ]
    ],
    [
        'name'  => 'GRAMS',
        'value' => 'grams',
        'label' => 'g',
        'meta'  => [
            'background_color' => 'bg-red-100',
            'text_color'       => 'text-red-800',
        ],
        'alias' => [
            'g',
        ]
        ...
    ]
]
```

## `names()`

Returns an array of all enum names.

### Usage

```php
VolumeUnitEnum::names();
VolumeUnitEnum::namesC(); // For collection
```

returns

```php
[
    'MILLIGRAMS',
    'GRAMS',
    'KILOGRAMS',
    'TONNE',
]
```

## `values()`

Returns an array of all enum values.

### Usage

```php
VolumeUnitEnum::values();
VolumeUnitEnum::valuesC(); // For collection
```

returns

```php
[
    'milligrams',
    'grams',
    'killograms',
    'tonne',
]
```

## `labels()`

Returns an array of all enum translations.

### Usage

```php
VolumeUnitEnum::labels();
VolumeUnitEnum::labelsC(); // For collection
```

returns

```php
[
    'mg',
    'g',
    'kg',
    't',
]
```

## `dict()`

Returns an array of all enum values mapping to their label.

### Usage

```php
VolumeUnitEnum::dict();
VolumeUnitEnum::dictC(); // For collection
```

returns

```php
[
    'MILLIGRAMS' => 'mg',
    'GRAMS'      => 'g',
    'KILOGRAMS'  => 'kg',
    'TONNE'      => 't',
]
```

## `toArray()` & `toCollection()`

Returns an array or collection of a single enum instance with its label, metadata and alias.

### Usage

```php
VolumeUnitEnum::MILLIGRAMS->toArray();
VolumeUnitEnum::MILLIGRAMS->toCollection(); // For collection
```

returns

```php
[
    'name'  => 'MILLIGRAMS',
    'value' => 'milligrams',
    'label' => 'mg',
    'meta'  => [
        'color'      => 'bg-green-100',
        'text_color' => 'text-green-800',
    ],
]
```

## `toHtml()`

An alias of ::label(). Used to satisfy Laravel's Htmlable interface.

### Usage

```php
VolumeUnitEnum::MILLIGRAMS->toHtml();
```

returns

```php
mg
```

## `toJson()`

Returns a json string represention of the `toArray()` return value.

## `is()`, `isA()` and `isAn()`

Allows you to check if an enum is a given value. Returns a boolean.
> **Note**
> `isA`, `isAn` are just aliases for `is`.

### Usage

```php
VolumeUnitEnum::MILLIGRAMS->is(VolumeUnitEnum::MILLIGRAMS); // true
VolumeUnitEnum::MILLIGRAMS->is('MILLIGRAMS');               // true
VolumeUnitEnum::MILLIGRAMS->is('invalid');                  // false
```

## `isNot()`, `isNotA()` and `isNotAn()`

Allows you to check if an enum is not a given value. Returns a boolean.
> **Note**
> `isNotA` and `isNotAn` are just aliases for `isNot`.

### Usage

```php
VolumeUnitEnum::MILLIGRAMS->isNot(VolumeUnitEnum::GRAMS); // true
VolumeUnitEnum::MILLIGRAMS->isNot('GRAMS');               // true
VolumeUnitEnum::MILLIGRAMS->isNot('invalid');             // true
VolumeUnitEnum::MILLIGRAMS->isNot('MILLIGRAMS');          // false
```

## `isAny()`

Allows you to check if an enum is contained in an array. Returns a boolean.

### Usage

```php
VolumeUnitEnum::MILLIGRAMS->isAny(['GRAMS', VolumeUnitEnum::TONNE]);                    // false
VolumeUnitEnum::MILLIGRAMS->isAny([VolumeUnitEnum::GRAMS, VolumeUnitEnum::MILLIGRAMS]); // true
```

## `isNotAny()`

Allows you to check if an enum is not contained in an array. Returns a boolean.

### Usage

```php
VolumeUnitEnum::MILLIGRAMS->isNotAny(['GRAMS', VolumeUnitEnum::TONNE]);                    // true
VolumeUnitEnum::MILLIGRAMS->isNotAny([VolumeUnitEnum::GRAMS, VolumeUnitEnum::MILLIGRAMS]); // false
```

## `rule()`

The enums may be validated using Laravel's standard Enum validation rule - `new Illuminate\Validation\Rules\Enum(VolumeUnitEnum::class)`.  
This method a shortcut for the validation rule. It supports specifying excluded cases.

### Usage

```php
public function rules(): array
{
    return [
        'volume_unit' => [VolumeUnitEnum::rule()],
    ];
}
```

## `matches()`

Find all matches and their respective levenshtein distance for a given input string. Can be used to determine what case is the closest match to the input string.

```php
VolumeUnitEnum::matches('ton');
```
returns

```php
[
    [
        'case' => VolumeUnitEnum::TONNE,
        'distance' => 2,
    ],
]
```

## `parse()`

Allows parsing any string value or enum instance to an enum instance. This method makes use of the `matches()` method in order to find the case with the closest distance. It also takes into account all defined `alias()`.

```php
TODO
```
returns 

```php
TODO
```
## `select()`

Highly customizable method to get data for select components.

```php
/**
* Parse this enums values into a valid format for select components.
*
* @param  null|string|self|array|Collection  $selected  The currently selected case(s).
* @param  null|string|self|array|Collection  $exclude  What enum case(s) should not be included.
* @param  null|string|Closure  $translation  When null the label() method is used. When a string, it uses the string value as a base translation path. When a closure, the result of the closure will be used.
* @param  string[]|Closure[]  $columns  Any additional columns to add to the result. Strings can be method or property names on the enum, closures will be called and the return value is used.
*/
public static function select(
    null|string|self|array|Collection $selected = null, 
    null|string|self|array|Collection $exclude = null, 
    null|string|Closure $translation = null, 
    string|Closure ...$columns): array
```
The `$selected` parameter determines which value(s) are currently selected. For a single select this is the case value of the enum instance. For a multiselect it is an array of case values of the enum instance.

The `$exclude` parameter should be either null, a string or enum instance or an array or collection of those. The provided case(s) will not be included in the result.

The `$translation` parameter is either a string referring to a method or property on the enum instance or a closure that gets the current string case and returns the actual string that should be used as the label. By default it will load the `label()` of the each enum case.

The `$columns` parameters are either strings referring to a method or property on the enum instance, or closures that should additionally be included in the resulting array data. 

# Other Classes

## AsFullEnumCollection

This cast is similar to the Laravel built in `AsEnumCollection` cast but unlike the built-in will maintain the full `toArray` structure
when converting to json.

E.g. the Laravel built in `AsEnumCollection` cast will return the following json:

```json
[
    "MILLIGRAMS",
    "GRAMS"
]
```

This cast will return

```json
[
    {
        "name": "MILLIGRAMS",
        "value": "MILLIGRAMS",
        "label": "mg",
        "meta": {
            "background_color": "bg-green-100",
            "text_color": "text-green-800"
        },
        "alias": [
            "mg"
        ],
    },
    {
        "name": "GRAMS",
        "value": "GRAMS",
        "label": "g",
        "meta": {
            "background_color": "bg-red-100",
            "text_color": "text-red-800"
        },
        "alias": [
            "g"
        ],
    }
]
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

This project was forked from version v2.6.1 of the foxbytehq/laravel-backed-enums repository, you might want to contribute to or use the base rather than the heavily cusomized version from me.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
