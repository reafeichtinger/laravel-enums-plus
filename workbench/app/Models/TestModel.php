<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Rea\LaravelEnumsPlus\Casts\AsFullEnumCollection;
use Workbench\App\Enums\VolumeUnitEnum;

/**
 * @property VolumeUnitEnum $cast_a
 * @property Collection<VolumeUnitEnum> $collection_a
 * @property Collection<VolumeUnitEnum> $collection_b
 */
class TestModel extends Model
{
    protected $fillable = [
        'cast_a',
        'collection_a',
        'collection_b',
    ];

    public function getCasts(): array
    {
        return [
            'cast_a' => VolumeUnitEnum::class,
            'collection_a' => AsFullEnumCollection::class . ':' . VolumeUnitEnum::class,
            'collection_b' => AsFullEnumCollection::class . ':' . VolumeUnitEnum::class,
        ];
    }
}
