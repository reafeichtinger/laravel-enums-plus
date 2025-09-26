<?php

use Workbench\App\Enums\VolumeUnitWithoutExtrasEnum;

return [
    // Notice the different translation from the VolumeUnitEnium.
    VolumeUnitWithoutExtrasEnum::class => [
        VolumeUnitWithoutExtrasEnum::MILLIGRAMS->value => 'mg',
        VolumeUnitWithoutExtrasEnum::GRAMS->value => 'g',
        VolumeUnitWithoutExtrasEnum::KILOGRAMS->value => 'kg',
        VolumeUnitWithoutExtrasEnum::TONNE->value => 't',
    ],
];
