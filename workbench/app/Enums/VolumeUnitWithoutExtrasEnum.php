<?php

namespace Workbench\App\Enums;

use Rea\LaravelEnumsPlus\EnumPlus;
use Rea\LaravelEnumsPlus\IsEnumPlus;

enum VolumeUnitWithoutExtrasEnum: string implements EnumPlus
{
    use IsEnumPlus;

    case MILLIGRAMS = 'milligrams';
    case GRAMS = 'grams';
    case KILOGRAMS = 'kilograms';
    case TONNE = 'tonne';
}
