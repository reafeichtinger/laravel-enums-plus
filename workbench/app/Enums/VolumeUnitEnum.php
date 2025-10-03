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

    public function withAlias(): array
    {
        return match ($this) {
            self::MILLIGRAMS => ['mg'],
            self::GRAMS => ['g'],
            self::KILOGRAMS => ['kg'],
            self::TONNE => ['t'],
            default => [],
        };
    }

    public function defaultCount(): int
    {
        return 0;
    }

    public function defaultReplace(): array
    {
        return [
            'of' => 'butter',
        ];
    }

    public function translations(): array
    {
        return [
            'en' => [
                self::GRAMS->value => ':count gram of :of|:count grams of :of',
                self::MILLIGRAMS->value => ':count milligram of :of|:count milligrams of :of',
                self::KILOGRAMS->value => ':count kilogram of :of|:count kilograms of :of',
                self::TONNE->value => ':count tonne of :of|:count tonnes of :of',
            ],
            'de' => [
                self::GRAMS->value => ':count Gram :Of|:count Gram :Of',
                self::MILLIGRAMS->value => ':count Milligram :Of|:count Milligram :Of',
                self::KILOGRAMS->value => ':count Kilogram :Of|:count Kilogram :Of',
                self::TONNE->value => ':count Tonne :Of|:count Tonnen :Of',
            ],
        ];
    }
}
