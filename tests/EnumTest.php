<?php

use Illuminate\Validation\Rules\Enum;
use Workbench\App\Enums\VolumeUnitEnum;
use Workbench\App\Enums\VolumeUnitWithoutExtrasEnum;

it('can create an enum', function () {
    expect(VolumeUnitEnum::GRAMS->value)->toEqual('grams');
});

it('can get the label', function () {
    // Dynamic translation from enum
    expect(VolumeUnitEnum::GRAMS->label())->toEqual('1 gram')
        ->and(VolumeUnitEnum::labelFor(VolumeUnitEnum::GRAMS))->toEqual('1 gram');

    expect(VolumeUnitEnum::GRAMS->label(2))->toEqual('2 grams')
        ->and(VolumeUnitEnum::labelFor(VolumeUnitEnum::GRAMS, 2))->toEqual('2 grams');

    $count = rand(2, 999);
    expect(VolumeUnitEnum::GRAMS->label($count, ['count' => "Weight = $count"]))->toEqual("Weight = $count grams")
        ->and(VolumeUnitEnum::labelFor(VolumeUnitEnum::GRAMS, $count, ['count' => "Weight = $count"]))->toEqual("Weight = $count grams");

    // Static translation from lang/en
    expect(VolumeUnitWithoutExtrasEnum::GRAMS->label())->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::labelFor(VolumeUnitWithoutExtrasEnum::GRAMS))->toEqual('g');

    expect(VolumeUnitWithoutExtrasEnum::GRAMS->label(2))->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::labelFor(VolumeUnitWithoutExtrasEnum::GRAMS, 2))->toEqual('g');
});

it('can get the metadata', function () {
    expect(VolumeUnitWithoutExtrasEnum::GRAMS->toArray())->toMatchArray([
        'meta' => [],
    ])
        ->and(VolumeUnitEnum::GRAMS->toArray())->toMatchArray([
            'meta' => [
                'background_color' => 'bg-red-100',
                'text_color' => 'text-red-800',
            ],
        ]);

});

it('can list all options', function () {
    expect(VolumeUnitEnum::options())->toEqual([
        [
            'name' => 'MILLIGRAMS',
            'value' => 'milligrams',
            'label' => '1 milligram',
            'meta' => [
                'background_color' => 'bg-green-100',
                'text_color' => 'text-green-800',
            ],
        ],
        [
            'name' => 'GRAMS',
            'value' => 'grams',
            'label' => '1 gram',
            'meta' => [
                'background_color' => 'bg-red-100',
                'text_color' => 'text-red-800',
            ],
        ],
        [
            'name' => 'KILOGRAMS',
            'value' => 'kilograms',
            'label' => '1 kilogram',
            'meta' => [
                'background_color' => 'bg-gray-100',
                'text_color' => 'text-gray-800',
            ],
        ],
        [
            'name' => 'TONNE',
            'value' => 'tonne',
            'label' => '1 tonne',
            'meta' => [
                'background_color' => 'bg-gray-100',
                'text_color' => 'text-gray-800',
            ],
        ],
    ]);
});

it('can get the names', function () {
    expect(VolumeUnitEnum::names())->toEqual([
        'MILLIGRAMS',
        'GRAMS',
        'KILOGRAMS',
        'TONNE',
    ]);
});

it('can get the values', function () {
    expect(VolumeUnitEnum::values())->toEqual([
        'milligrams',
        'grams',
        'kilograms',
        'tonne',
    ]);
});

it('can get the labels', function () {
    expect(VolumeUnitEnum::labels())->toEqual([
        '1 milligram',
        '1 gram',
        '1 kilogram',
        '1 tonne',
    ]);
});

it('can get the value => label map', function () {
    expect(VolumeUnitEnum::map())->toEqual([
        'milligrams' => '1 milligram',
        'grams' => '1 gram',
        'kilograms' => '1 kilogram',
        'tonne' => '1 tonne',
    ]);
});

it('can convert a single value to an array', function () {
    expect(VolumeUnitEnum::MILLIGRAMS->toArray())->toEqual([
        'name' => 'MILLIGRAMS',
        'value' => 'milligrams',
        'label' => '1 milligram',
        'meta' => [
            'background_color' => 'bg-green-100',
            'text_color' => 'text-green-800',
        ],
    ]);
});

it('will return the label if toHtml it called', function () {
    expect(VolumeUnitEnum::MILLIGRAMS->toHtml())->toEqual('1 milligram');
});

it('will return the json string format of toArray if toJson is called', function () {
    expect(VolumeUnitEnum::MILLIGRAMS->toJson())->toEqual('{"name":"MILLIGRAMS","value":"milligrams","label":"1 milligram","meta":{"background_color":"bg-green-100","text_color":"text-green-800"}}');
});

it('can compare enums', function () {
    // is methods
    expect(VolumeUnitEnum::MILLIGRAMS->is(VolumeUnitEnum::MILLIGRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->is(VolumeUnitEnum::GRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->is('milligrams'))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->is('grams'))->toBeFalse()
        // isA methods
        ->and(VolumeUnitEnum::MILLIGRAMS->isA(VolumeUnitEnum::MILLIGRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isA(VolumeUnitEnum::GRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isA('milligrams'))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isA('grams'))->toBeFalse()
        // isAn methods
        ->and(VolumeUnitEnum::MILLIGRAMS->isAn(VolumeUnitEnum::MILLIGRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isAn(VolumeUnitEnum::GRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isAn('milligrams'))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isAn('grams'))->toBeFalse()
        // isAny methods
        ->and(VolumeUnitEnum::MILLIGRAMS->isAny([VolumeUnitEnum::MILLIGRAMS, VolumeUnitEnum::TONNE]))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isAny([VolumeUnitEnum::GRAMS, VolumeUnitEnum::TONNE]))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isAny(['milligrams', 'tonne']))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isAny(['grams', 'tonne']))->toBeFalse();
});

it('can negative compare enums', function () {
    expect(VolumeUnitEnum::MILLIGRAMS->isNot(VolumeUnitEnum::MILLIGRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNot(VolumeUnitEnum::GRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNot('milligrams'))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNot('grams'))->toBeTrue()
        //
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA(VolumeUnitEnum::MILLIGRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA(VolumeUnitEnum::GRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA('milligrams'))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA('grams'))->toBeTrue()
        //
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAn(VolumeUnitEnum::MILLIGRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAn(VolumeUnitEnum::GRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAn('milligrams'))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAn('grams'))->toBeTrue()
        // isNotAny methods
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAny([VolumeUnitEnum::MILLIGRAMS, VolumeUnitEnum::TONNE]))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAny([VolumeUnitEnum::GRAMS, VolumeUnitEnum::TONNE]))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAny(['milligrams', 'tonne']))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotAny(['grams', 'tonne']))->toBeTrue();
});

it('throws an exception when comparing against an invalid value', function () {
    expect(fn () => VolumeUnitEnum::MILLIGRAMS->is('invalid'))
        ->toThrow('"invalid" is not a valid backing value for enum Workbench\App\Enums\VolumeUnitEnum');
});

it('can get the validation rule', function () {
    expect(VolumeUnitEnum::rule())->toBeInstanceOf(Enum::class);
});
