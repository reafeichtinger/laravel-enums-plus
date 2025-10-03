<?php

namespace Workbench\App;

use Rea\LaravelEnumsPlus\EnumPlus;
use Rea\LaravelEnumsPlus\IsEnumPlus;

enum StringEnum: {{ type }} implements EnumPlus
{
    use IsEnumPlus;

    /**
     * Add your Enums below using.
     * e.g. case Standard = 'standard';
     */
}
