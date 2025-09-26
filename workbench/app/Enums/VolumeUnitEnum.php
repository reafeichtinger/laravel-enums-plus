<?php

namespace Workbench\App\Enums;

use Rea\LaravelEnumsPlus\EnumPlus;
use Rea\LaravelEnumsPlus\IsEnumPlus;

enum VolumeUnitEnum: string implements EnumPlus
{
    use IsEnumPlus;

    case MILLIGRAMS = 'milligrams';
    case GRAMS = 'grams';
    case KILOGRAMS = 'kilograms';
    case TONNE = 'tonne';

    public function withMeta(): array
    {
        return match ($this) {
            self::MILLIGRAMS => [
                'background_color' => 'bg-green-100',
                'text_color' => 'text-green-800',
            ],
            self::GRAMS => [
                'background_color' => 'bg-red-100',
                'text_color' => 'text-red-800',
            ],
            self::KILOGRAMS, self::TONNE => [
                'background_color' => 'bg-gray-100',
                'text_color' => 'text-gray-800',
            ],
            default => [
                'background_color' => 'bg-blue-100',
                'text_color' => 'text-blue-800',
            ],
        };
    }

    public function translations(): array
    {
        return [
            'en' => [
                self::GRAMS->value => ':count gram|:count grams',
                self::MILLIGRAMS->value => ':count milligram|:count milligrams',
                self::KILOGRAMS->value => ':count kilogram|:count kilograms',
                self::TONNE->value => ':count tonne|:count tonnes',
            ],
            'de' => [
                self::GRAMS->value => ':count Gram|:count Gram',
                self::MILLIGRAMS->value => ':count Milligram|:count Milligram',
                self::KILOGRAMS->value => ':count Kilogram|:count Kilogram',
                self::TONNE->value => ':count Tonne|:count Tonnen',
            ],
        ];
    }
}
