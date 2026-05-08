<?php

namespace App\Services\Iqair;

use App\Models\IqairMeasurement;
use Illuminate\Support\Collection;

class IqairMeasurementListing
{
    /**
     * @return Collection<int, array{
     *     id: int,
     *     city: string,
     *     state: string,
     *     country: string,
     *     latitude: float|null,
     *     longitude: float|null,
     *     pollution_ts: string|null,
     *     aqius: int|null,
     *     mainus: string|null,
     *     aqicn: int|null,
     *     maincn: string|null,
     *     weather_ts: string|null,
     *     temperature: int|null,
     *     pressure: int|null,
     *     humidity: int|null,
     *     wind_speed: float|null,
     *     wind_direction: int|null,
     *     weather_icon: string|null
     * }>
     */
    public function latest(): Collection
    {
        return IqairMeasurement::query()
            ->select([
                'id',
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
            ])
            ->latest('id')
            ->get()
            ->map(fn (IqairMeasurement $measurement): array => [
                'id' => $measurement->id,
                'city' => $measurement->city->value,
                'state' => $measurement->state->value,
                'country' => $measurement->country->value,
                'latitude' => $measurement->latitude,
                'longitude' => $measurement->longitude,
                'pollution_ts' => $measurement->pollution_ts?->toIso8601String(),
                'aqius' => $measurement->aqius,
                'mainus' => $measurement->mainus,
                'aqicn' => $measurement->aqicn,
                'maincn' => $measurement->maincn,
                'weather_ts' => $measurement->weather_ts?->toIso8601String(),
                'temperature' => $measurement->temperature,
                'pressure' => $measurement->pressure,
                'humidity' => $measurement->humidity,
                'wind_speed' => $measurement->wind_speed,
                'wind_direction' => $measurement->wind_direction,
                'weather_icon' => $measurement->weather_icon,
            ]);
    }
}
