<?php

use Illuminate\Validation\Rules\Enum;
use Workbench\App\Enums\EnumWithoutInterface;
use Workbench\App\Enums\VolumeUnitEnum;
use Workbench\App\Enums\VolumeUnitWithoutExtrasEnum;

it('can create an enum', function () {
    $instance = VolumeUnitEnum::GRAMS;
    expect($instance)->toEqual($instance);
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

it('can get the metadata array and collection', function () {
    $empty = ['meta' => []];
    $expected = [
        'meta' => [
            'background_color' => 'bg-red-100',
            'text_color' => 'text-red-800',
        ],
    ];

    expect(VolumeUnitWithoutExtrasEnum::GRAMS->toArray())->toMatchArray($empty)
        ->and(VolumeUnitEnum::GRAMS->toArray())->toMatchArray($expected);

    expect(VolumeUnitWithoutExtrasEnum::GRAMS->toCollection())->toMatchArray($empty)
        ->and(VolumeUnitEnum::GRAMS->toCollection())->toMatchArray($expected);
});

it('can list all options as array and collection', function () {
    $expected = [
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
    ];
    expect(VolumeUnitEnum::options())->toMatchArray($expected);
    expect(VolumeUnitEnum::optionsC())->toMatchArray($expected);
});

it('can get the names as array and collection', function () {
    $expected = [
        'MILLIGRAMS',
        'GRAMS',
        'KILOGRAMS',
        'TONNE',
    ];
    expect(VolumeUnitEnum::names())->toMatchArray($expected);
    expect(VolumeUnitEnum::namesC())->toMatchArray($expected);
});

it('can get the values as array and collection', function () {
    $expected = [
        'milligrams',
        'grams',
        'kilograms',
        'tonne',
    ];
    expect(VolumeUnitEnum::values())->toMatchArray($expected);
    expect(VolumeUnitEnum::valuesC())->toMatchArray($expected);
});

it('can get the labels as array and collection', function () {
    $expected = [
        '1 milligram',
        '1 gram',
        '1 kilogram',
        '1 tonne',
    ];
    expect(VolumeUnitEnum::labels())->toMatchArray($expected);
    expect(VolumeUnitEnum::labelsC())->toMatchArray($expected);
});

it('can get the value => label dictionary as array and collection', function () {
    $expected = [
        'milligrams' => '1 milligram',
        'grams' => '1 gram',
        'kilograms' => '1 kilogram',
        'tonne' => '1 tonne',
    ];
    expect(VolumeUnitEnum::dict())->toMatchArray($expected);
    expect(VolumeUnitEnum::dictC())->toMatchArray($expected);
});

it('can convert a single value to an array and collection', function () {
    $expected = [
        'name' => 'MILLIGRAMS',
        'value' => 'milligrams',
        'label' => '1 milligram',
        'meta' => [
            'background_color' => 'bg-green-100',
            'text_color' => 'text-green-800',
        ],
    ];
    expect(VolumeUnitEnum::MILLIGRAMS->toArray())->toMatchArray($expected);
    expect(VolumeUnitEnum::MILLIGRAMS->toCollection())->toMatchArray($expected);
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
    // isNot methods
    expect(VolumeUnitEnum::MILLIGRAMS->isNot(VolumeUnitEnum::MILLIGRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNot(VolumeUnitEnum::GRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNot('milligrams'))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNot('grams'))->toBeTrue()
        // isNotA methods
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA(VolumeUnitEnum::MILLIGRAMS))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA(VolumeUnitEnum::GRAMS))->toBeTrue()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA('milligrams'))->toBeFalse()
        ->and(VolumeUnitEnum::MILLIGRAMS->isNotA('grams'))->toBeTrue()
        // isNotAn methods
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

it('can get the validation rule', function () {
    expect(VolumeUnitEnum::rule())->toBeInstanceOf(Enum::class);
    expect((fn () => $this->only)->call(VolumeUnitEnum::rule()))->toMatchArray(['milligrams', 'grams', 'kilograms', 'tonne']);
    expect((fn () => $this->only)->call(VolumeUnitEnum::rule(exclude: VolumeUnitEnum::TONNE)))->toMatchArray(['milligrams', 'grams', 'kilograms']);
});

it('doesn\'t throw an exception when comparing against an invalid value', function () {
    expect(VolumeUnitEnum::MILLIGRAMS->is('not-an-enum-value'))->toBe(false);
});

it('does throw an exception when not implementing the EnumPlus interface', function () {
    EnumWithoutInterface::values();
})->throws(RuntimeException::class);
