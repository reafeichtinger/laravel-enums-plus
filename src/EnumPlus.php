<?php

namespace Rea\LaravelEnumsPlus;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;

interface EnumPlus extends Arrayable, Htmlable, Jsonable {}
