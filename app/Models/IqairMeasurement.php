<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'city',
    'state',
    'country',
    'latitude',
    'longitude',
    'pollution_ts',
    'aqius',
    'mainus',
    'aqicn',
    'maincn',
    'weather_ts',
    'temperature',
    'pressure',
    'humidity',
    'wind_speed',
    'wind_direction',
    'weather_icon',
])]
class IqairMeasurement extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'pollution_ts' => 'datetime',
            'aqius' => 'integer',
            'aqicn' => 'integer',
            'weather_ts' => 'datetime',
            'temperature' => 'integer',
            'pressure' => 'integer',
            'humidity' => 'integer',
            'wind_speed' => 'float',
            'wind_direction' => 'integer',
        ];
    }
}
