<?php

namespace Workbench\App\Enums;

use Rea\LaravelEnumsPlus\EnumPlus;
use Rea\LaravelEnumsPlus\IsEnumPlus;

enum StringEnum: string implements EnumPlus
{
    use IsEnumPlus;

    /**
     * Add your Enums below using.
     * e.g. case Standard = 'standard';
     */
}
