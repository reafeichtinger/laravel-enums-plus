<?php

use Illuminate\Support\Facades\Config;
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
    expect(VolumeUnitEnum::GRAMS->label())->toEqual('0 grams of butter')
        ->and(VolumeUnitEnum::GRAMS->trans())->toEqual('0 grams of butter')
        ->and(VolumeUnitEnum::labelFor(VolumeUnitEnum::GRAMS))->toEqual('0 grams of butter')
        ->and(VolumeUnitEnum::transFor(VolumeUnitEnum::GRAMS))->toEqual('0 grams of butter');

    expect(VolumeUnitEnum::GRAMS->label(2))->toEqual('2 grams of butter')
        ->and(VolumeUnitEnum::GRAMS->label(2))->toEqual('2 grams of butter')
        ->and(VolumeUnitEnum::labelFor(VolumeUnitEnum::GRAMS, 2))->toEqual('2 grams of butter')
        ->and(VolumeUnitEnum::transFor(VolumeUnitEnum::GRAMS, 2))->toEqual('2 grams of butter');

    $count = rand(2, 999);
    expect(VolumeUnitEnum::GRAMS->label($count, ['count' => "Weight = $count", 'of' => 'ketchup']))->toEqual("Weight = $count grams of ketchup")
        ->and(VolumeUnitEnum::GRAMS->trans($count, ['count' => "Weight = $count", 'of' => 'ketchup']))->toEqual("Weight = $count grams of ketchup")
        ->and(VolumeUnitEnum::labelFor(VolumeUnitEnum::GRAMS, $count, ['count' => "Weight = $count", 'of' => 'ketchup']))->toEqual("Weight = $count grams of ketchup")
        ->and(VolumeUnitEnum::transFor(VolumeUnitEnum::GRAMS, $count, ['count' => "Weight = $count", 'of' => 'ketchup']))->toEqual("Weight = $count grams of ketchup");

    // Static translation from lang/en
    expect(VolumeUnitWithoutExtrasEnum::GRAMS->label())->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::GRAMS->trans())->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::labelFor(VolumeUnitWithoutExtrasEnum::GRAMS))->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::transFor(VolumeUnitWithoutExtrasEnum::GRAMS))->toEqual('g');

    expect(VolumeUnitWithoutExtrasEnum::GRAMS->label(2))->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::GRAMS->trans(2))->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::labelFor(VolumeUnitWithoutExtrasEnum::GRAMS, 2))->toEqual('g')
        ->and(VolumeUnitWithoutExtrasEnum::transFor(VolumeUnitWithoutExtrasEnum::GRAMS, 2))->toEqual('g');
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
            'label' => '0 milligrams of butter',
            'meta' => [
                'background_color' => 'bg-green-100',
                'text_color' => 'text-green-800',
            ],
            'alias' => [
                'mg',
            ],
        ],
        [
            'name' => 'GRAMS',
            'value' => 'grams',
            'label' => '0 grams of butter',
            'meta' => [
                'background_color' => 'bg-red-100',
                'text_color' => 'text-red-800',
            ],
            'alias' => [
                'g',
            ],
        ],
        [
            'name' => 'KILOGRAMS',
            'value' => 'kilograms',
            'label' => '0 kilograms of butter',
            'meta' => [
                'background_color' => 'bg-gray-100',
                'text_color' => 'text-gray-800',
            ],
            'alias' => [
                'kg',
            ],
        ],
        [
            'name' => 'TONNE',
            'value' => 'tonne',
            'label' => '0 tonnes of butter',
            'meta' => [
                'background_color' => 'bg-gray-100',
                'text_color' => 'text-gray-800',
            ],
            'alias' => [
                't',
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
        '0 milligrams of butter',
        '0 grams of butter',
        '0 kilograms of butter',
        '0 tonnes of butter',
    ];
    expect(VolumeUnitEnum::labels())->toMatchArray($expected);
    expect(VolumeUnitEnum::labelsC())->toMatchArray($expected);
});

it('can get the labels with custom translation path', function () {
    Config::set('enums-plus.translations', 'custom/enums');
    $expected = [
        'custom mg',
        'custom g',
        'custom kg',
        'custom t',
    ];
    expect(VolumeUnitWithoutExtrasEnum::labels())->toMatchArray($expected);
    expect(VolumeUnitWithoutExtrasEnum::labelsC())->toMatchArray($expected);
});

it('can get the value => label dictionary as array and collection', function () {
    $expected = [
        'milligrams' => '0 milligrams of butter',
        'grams' => '0 grams of butter',
        'kilograms' => '0 kilograms of butter',
        'tonne' => '0 tonnes of butter',
    ];
    expect(VolumeUnitEnum::dict())->toMatchArray($expected);
    expect(VolumeUnitEnum::dictC())->toMatchArray($expected);
});

it('can convert a single value to an array and collection', function () {
    $expected = [
        'name' => 'MILLIGRAMS',
        'value' => 'milligrams',
        'label' => '0 milligrams of butter',
        'meta' => [
            'background_color' => 'bg-green-100',
            'text_color' => 'text-green-800',
        ],
    ];
    expect(VolumeUnitEnum::MILLIGRAMS->toArray())->toMatchArray($expected);
    expect(VolumeUnitEnum::MILLIGRAMS->toCollection())->toMatchArray($expected);
});

it('will return the label if toHtml it called', function () {
    expect(VolumeUnitEnum::MILLIGRAMS->toHtml())->toEqual('0 milligrams of butter');
});

it('will return the json string format of toArray if toJson is called', function () {
    expect(VolumeUnitEnum::MILLIGRAMS->toJson())->toEqual('{"name":"MILLIGRAMS","value":"milligrams","label":"0 milligrams of butter","meta":{"background_color":"bg-green-100","text_color":"text-green-800"},"alias":["mg"]}');
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

it('can get the seletion array', function () {
    $expected = [
        ['value' => 'milligrams', 'label' => '0 milligrams of butter'],
        ['value' => 'grams', 'label' => '0 grams of butter'],
        ['value' => 'kilograms', 'label' => '0 kilograms of butter'],
        ['value' => 'tonne', 'label' => '0 tonnes of butter'],
    ];
    expect(VolumeUnitEnum::select())->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC())->toMatchArray($expected);
});

it('can get the seletion array with a custom string column', function () {
    $expected = [
        ['value' => 'milligrams', 'label' => '0 milligrams of butter', 'name' => 'MILLIGRAMS'],
        ['value' => 'grams', 'label' => '0 grams of butter', 'name' => 'GRAMS'],
        ['value' => 'kilograms', 'label' => '0 kilograms of butter', 'name' => 'KILOGRAMS'],
        ['value' => 'tonne', 'label' => '0 tonnes of butter', 'name' => 'TONNE'],
    ];
    expect(VolumeUnitEnum::select(name: 'name'))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(name: 'name'))->toMatchArray($expected);
});

it('can get the seletion array with a custom closure column', function () {
    $expected = [
        ['value' => 'milligrams', 'label' => '0 milligrams of butter', 'name' => 'MILLIGRAMS_TEST'],
        ['value' => 'grams', 'label' => '0 grams of butter', 'name' => 'GRAMS_TEST'],
        ['value' => 'kilograms', 'label' => '0 kilograms of butter', 'name' => 'KILOGRAMS_TEST'],
        ['value' => 'tonne', 'label' => '0 tonnes of butter', 'name' => 'TONNE_TEST'],
    ];
    expect(VolumeUnitEnum::select(name: fn ($case) => $case->name . '_TEST'))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(name: fn ($case) => $case->name . '_TEST'))->toMatchArray($expected);
});

it('can overwrite a default column', function () {
    $expected = [
        ['value' => 'milligrams', 'label' => '2 milligrams of butter'],
        ['value' => 'grams', 'label' => '2 grams of butter'],
        ['value' => 'kilograms', 'label' => '2 kilograms of butter'],
        ['value' => 'tonne', 'label' => '2 tonnes of butter'],
    ];
    expect(VolumeUnitEnum::select(label: fn ($case) => $case->label(2)))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(label: fn ($case) => $case->label(2)))->toMatchArray($expected);
});

it('can mark values as selected', function () {
    $expected = [
        ['value' => 'milligrams', 'label' => '0 milligrams of butter', 'selected' => true],
        ['value' => 'grams', 'label' => '0 grams of butter'],
        ['value' => 'kilograms', 'label' => '0 kilograms of butter'],
        ['value' => 'tonne', 'label' => '0 tonnes of butter'],
    ];

    // Single values
    expect(VolumeUnitEnum::select(selected: VolumeUnitEnum::MILLIGRAMS))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(selected: VolumeUnitEnum::MILLIGRAMS))->toMatchArray($expected);

    expect(VolumeUnitEnum::select(selected: VolumeUnitEnum::MILLIGRAMS->value))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(selected: VolumeUnitEnum::MILLIGRAMS->value))->toMatchArray($expected);

    // Multiple values
    $expected[2]['selected'] = true;
    expect(VolumeUnitEnum::select(selected: [VolumeUnitEnum::MILLIGRAMS, VolumeUnitEnum::KILOGRAMS]))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(selected: [VolumeUnitEnum::MILLIGRAMS, VolumeUnitEnum::KILOGRAMS]))->toMatchArray($expected);

    expect(VolumeUnitEnum::select(selected: [VolumeUnitEnum::MILLIGRAMS->value, VolumeUnitEnum::KILOGRAMS->value]))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(selected: [VolumeUnitEnum::MILLIGRAMS->value, VolumeUnitEnum::KILOGRAMS->value]))->toMatchArray($expected);

    expect(VolumeUnitEnum::select(selected: [VolumeUnitEnum::MILLIGRAMS->value, VolumeUnitEnum::KILOGRAMS]))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(selected: [VolumeUnitEnum::MILLIGRAMS->value, VolumeUnitEnum::KILOGRAMS]))->toMatchArray($expected);
});

it('can get the seletion array with custom config values', function () {
    Config::set('enums-plus.columns.value', 'id');
    Config::set('enums-plus.columns.label', 'name');
    Config::set('enums-plus.columns.selected', 'checked');
    $expected = [
        ['id' => 'milligrams', 'name' => '0 milligrams of butter', 'checked' => true],
        ['id' => 'grams', 'name' => '0 grams of butter'],
        ['id' => 'kilograms', 'name' => '0 kilograms of butter'],
        ['id' => 'tonne', 'name' => '0 tonnes of butter'],
    ];
    expect(VolumeUnitEnum::select(VolumeUnitEnum::MILLIGRAMS))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(VolumeUnitEnum::MILLIGRAMS))->toMatchArray($expected);
});

it('can overwrite the default translation', function () {
    // Custom translation path
    $expected = [
        ['value' => 'milligrams', 'label' => 'custom/translation.path.milligrams'],
        ['value' => 'grams', 'label' => 'custom/translation.path.grams'],
        ['value' => 'kilograms', 'label' => 'custom/translation.path.kilograms'],
        ['value' => 'tonne', 'label' => 'custom/translation.path.tonne'],
    ];
    expect(VolumeUnitEnum::select(translation: 'custom/translation.path'))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(translation: 'custom/translation.path'))->toMatchArray($expected);

    // Custom translation closure
    $expected = [
        ['value' => 'milligrams', 'label' => '99 milligrams of butter'],
        ['value' => 'grams', 'label' => '99 grams of butter'],
        ['value' => 'kilograms', 'label' => '99 kilograms of butter'],
        ['value' => 'tonne', 'label' => '99 tonnes of butter'],
    ];
    expect(VolumeUnitEnum::select(translation: fn ($case) => $case->label(99)))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(translation: fn ($case) => $case->label(99)))->toMatchArray($expected);
});

it('can exclude enum cases', function () {
    $expected = [
        ['value' => 'milligrams', 'label' => '0 milligrams of butter'],
        ['value' => 'kilograms', 'label' => '0 kilograms of butter'],
        ['value' => 'tonne', 'label' => '0 tonnes of butter'],
    ];
    expect(VolumeUnitEnum::select(exclude: VolumeUnitEnum::GRAMS))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(exclude: VolumeUnitEnum::GRAMS))->toMatchArray($expected);

    expect(VolumeUnitEnum::select(exclude: VolumeUnitEnum::GRAMS->value))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(exclude: VolumeUnitEnum::GRAMS->value))->toMatchArray($expected);

    $expected = [
        ['value' => 'milligrams', 'label' => '0 milligrams of butter'],
        ['value' => 'kilograms', 'label' => '0 kilograms of butter'],
    ];
    expect(VolumeUnitEnum::select(exclude: [VolumeUnitEnum::GRAMS, VolumeUnitEnum::TONNE]))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(exclude: [VolumeUnitEnum::GRAMS, VolumeUnitEnum::TONNE]))->toMatchArray($expected);

    expect(VolumeUnitEnum::select(exclude: [VolumeUnitEnum::GRAMS->value, VolumeUnitEnum::TONNE->value]))->toMatchArray($expected);
    expect(VolumeUnitEnum::selectC(exclude: [VolumeUnitEnum::GRAMS->value, VolumeUnitEnum::TONNE->value]))->toMatchArray($expected);
});

it('can get matches', function () {
    $expected = [
        [
            'case' => [
                'name' => 'TONNE',
                'value' => 'tonne',
                'label' => '0 tonnes of butter',
                'meta' => [
                    'background_color' => 'bg-gray-100',
                    'text_color' => 'text-gray-800',
                ],
                'alias' => ['t'],
            ],
            'distance' => 2,
        ],
    ];

    expect(VolumeUnitEnum::matches('ton'))->toMatchArray($expected);
    expect(VolumeUnitEnum::matchesC('ton'))->toMatchArray($expected);
});

it('can parse any value to some case', function () {
    expect(VolumeUnitEnum::parse('tonne'))->toBe(VolumeUnitEnum::TONNE);
    expect(VolumeUnitEnum::parse('t'))->toBe(VolumeUnitEnum::TONNE);
    expect(VolumeUnitEnum::parse('ton'))->toBe(VolumeUnitEnum::TONNE);
    expect(VolumeUnitEnum::parse('tone'))->toBe(VolumeUnitEnum::TONNE);
    expect(VolumeUnitEnum::parse('Gramm'))->toBe(VolumeUnitEnum::GRAMS);
    expect(VolumeUnitEnum::parse('gr4m'))->toBe(VolumeUnitEnum::GRAMS);
    expect(VolumeUnitEnum::parse(''))->toBe(VolumeUnitEnum::GRAMS);
    expect(VolumeUnitEnum::parse('whatever'))->toBe(VolumeUnitEnum::TONNE);
    expect(VolumeUnitEnum::parse('gram of'))->toBe(VolumeUnitEnum::GRAMS);
});
