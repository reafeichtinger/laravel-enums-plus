<?php

use Workbench\App\Enums\VolumeUnitWithoutExtrasEnum;

return [
    // Notice the different translation from the VolumeUnitEnium.
    VolumeUnitWithoutExtrasEnum::class => [
        VolumeUnitWithoutExtrasEnum::MILLIGRAMS->value => 'custom mg',
        VolumeUnitWithoutExtrasEnum::GRAMS->value => 'custom g',
        VolumeUnitWithoutExtrasEnum::KILOGRAMS->value => 'custom kg',
        VolumeUnitWithoutExtrasEnum::TONNE->value => 'custom t',
    ],
];
