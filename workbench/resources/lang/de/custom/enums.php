<?php

use Workbench\App\Enums\VolumeUnitWithoutExtrasEnum;

return [
    // Notice the different translation from the VolumeUnitEnium.
    VolumeUnitWithoutExtrasEnum::class => [
        VolumeUnitWithoutExtrasEnum::MILLIGRAMS->value => 'Custom mg',
        VolumeUnitWithoutExtrasEnum::GRAMS->value => 'Custom g',
        VolumeUnitWithoutExtrasEnum::KILOGRAMS->value => 'Custom kg',
        VolumeUnitWithoutExtrasEnum::TONNE->value => 'Custom t',
    ],
];
