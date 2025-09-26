<?php

namespace Workbench\App\Enums;

use Rea\LaravelEnumsPlus\IsEnumPlus;

enum EnumWithoutInterface: string
{
    use IsEnumPlus;
}
