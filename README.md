![Banner Image](https://banners.beyondco.de/Laravel%20Enums%20Plus.png?theme=light&packageManager=composer+require&packageName=reafeichtinger%2Flaravel-enums-plus&pattern=architect&style=style_1&description=Supercharge+your+PHP8+enums+in+Laravel.&md=1&showWatermark=0&fontSize=125px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/reafeichtinger/laravel-enums-plus.svg?style=flat-square)](https://packagist.org/packages/reafeichtinger/laravel-enums-plus)
[![Total Downloads](https://img.shields.io/packagist/dt/reafeichtinger/laravel-enums-plus.svg?style=flat-square)](https://packagist.org/packages/reafeichtinger/laravel-enums-plus)

This package supercharges your PHP8 enums with superpowers like localization support and fluent comparison methods.

## Installation

```bash
composer require reafeichtinger/laravel-enums-plus
```

## Usage

### Make Command

Creating a new Laravel Enum is easy with the make:enum command.

#### Command:

```Bash
php artisan make:enum {name} --string # or --int
```

#### Arguments:

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

### Upgrade your existing enums

The enum you create must implement the `EnumPlus` interface and also use the `IsEnumPlus` trait.
The interface is required for Laravel to cast your enum correctly and the trait is what gives your enum its superpowers.

```php
use Rea\LaravelEnumsPlus\EnumPlus;
use Rea\LaravelEnumsPlus\IsEnumPlus;

enum VolumeUnitEnum: string implements EnumPlus
{
    use IsEnumPlus;

    case MILLIGRAMS = "milligrams";
    case GRAMS = "grams";
    case KILOGRAMS = "kilograms";
    case TONNE = "tonne";
}
```

### Enum value labels (Localization)

Create enums.php lang file and create labels for your enum values.

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

You may then access these localized values using the `->label()` or `::labelFor()` methods.  
Additionally rendering the enum in a blade template will render the label.

```php
VolumeUnitEnum::MILLIGRAMS->label(); // "mg"
VolumeUnitEnum::labelFor(VolumeUnitEnum::TONNE); // "t"
// in blade
{{ VolumeUnitEnum::KILOGRAMS }} // "kg"
```

If you do not specify a label in the lang file these methods will return the value assigned to the enum inside the enum file. e.g MILLIGRAMS label will be milligrams.

#### Dynamic translations

You can also add translations directly to the enum file. Translations support the full feature catalouge of laravel translations, meaning pluralization and placeholders.

Create a `translations` method on your enum.

```php
public function translations(): array
{
    return [
        'en' => [
            self::GRAMS->value => ':count gram|:count grams',
            self::MILLIGRAMS->value => ':count milligram|:count milligrams',
            self::KILOGRAMS->value => ':count kilogram|:count kilograms',
            self::TONNE->value => ':count tonne|:count tonnes',
        ],
        'de' => [
            self::GRAMS->value => ':count Gram|:count Gram',
            self::MILLIGRAMS->value => ':count Milligram|:count Milligram',
            self::KILOGRAMS->value => ':count Kilogram|:count Kilogram',
            self::TONNE->value => ':count Tonne|:count Tonnen',
        ],
    ];
}
```

If you do not specify a `translations` method, it will fallback to the enums.php translation file.

### Meta data

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

## Other methods

### options

Returns an array of all enum values with their labels and metadata.

#### Usage

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
    ],
    [
        'name'  => 'GRAMS',
        'value' => 'grams',
        'label' => 'g',
        'meta'  => [
            'background_color' => 'bg-red-100',
            'text_color'       => 'text-red-800',
        ],
        ...
    ]
]
```

### names

Returns an array of all enum values.

#### Usage

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

### values

Returns an array of all enum values.

#### Usage

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

### labels

Returns an array of all enum labels.

#### Usage

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

### dict

Returns an array of all enum values mapping to their label.

#### Usage

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

### toArray & toCollection

Returns an array of a single enum value with its label and metadata.

#### Usage

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

### toHtml

An alias of ::label(). Used to satisfy Laravel's Htmlable interface.

#### Usage

```php
VolumeUnitEnum::MILLIGRAMS->toHtml();
```

returns

```php
mg
```

### toJson

Returns a json string represention of the toArray return value.

### is/isA/isAn

Allows you to check if an enum is a given value. Returns a boolean.
> **Note**
> `isA`, `isAn` are just aliases for `is`.

#### Usage

```php
VolumeUnitEnum::MILLIGRAMS->is(VolumeUnitEnum::MILLIGRAMS); //true
VolumeUnitEnum::MILLIGRAMS->is('MILLIGRAMS');               //true
VolumeUnitEnum::MILLIGRAMS->is('invalid');                  //exception
```

### isNot/isNotA/isNotAn

Allows you to check if an enum is not a given value. Returns a boolean.
> **Note**
> `isNotA` and `isNotAn` are just aliases for `isNot`.

#### Usage

```php
VolumeUnitEnum::MILLIGRAMS->isNot(VolumeUnitEnum::GRAMS); //true
VolumeUnitEnum::MILLIGRAMS->isNot('GRAMS');               //true
VolumeUnitEnum::MILLIGRAMS->isNot('invalid');             //exception
```

### isAny

Allows you to check if an enum is contained in an array. Returns a boolean.

#### Usage

```php
VolumeUnitEnum::MILLIGRAMS->isAny(['GRAMS', VolumeUnitEnum::TONNE]);                    // false
VolumeUnitEnum::MILLIGRAMS->isAny([VolumeUnitEnum::GRAMS, VolumeUnitEnum::MILLIGRAMS]); // true
```

### isNotAny

Allows you to check if an enum is not contained in an array. Returns a boolean.

#### Usage

```php
VolumeUnitEnum::MILLIGRAMS->isNotAny(['GRAMS', VolumeUnitEnum::TONNE]);                    // true
VolumeUnitEnum::MILLIGRAMS->isNotAny([VolumeUnitEnum::GRAMS, VolumeUnitEnum::MILLIGRAMS]); // false
```

### rule

The enums may be validated using Laravel's standard Enum validation rule - `new Illuminate\Validation\Rules\Enum(VolumeUnitEnum::class)`.  
This method a shortcut for the validation rule.

#### Usage

```
public function rules(): array
{
    return [
        'volume_unit' => [VolumeUnitEnum::rule()],
    ];
}
```

## Other Classes

### AsFullEnumCollection

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
        }
    },
    {
        "name": "GRAMS",
        "value": "GRAMS",
        "label": "g",
        "meta": {
            "background_color": "bg-red-100",
            "text_color": "text-red-800"
        }
    }
]
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

This project was forked from version v2.6.1 of the foxbytehq/laravel-backed-enums repository, you might want to contribute to or use the base rather than the heavily cusomized version from me.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
