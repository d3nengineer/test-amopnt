<?php

namespace App\Services\Iqair;

use App\Enums\IqairCity;
use App\Enums\IqairCountry;
use App\Enums\IqairState;
use App\Models\IqairMeasurement;
use App\Services\Iqair\Exceptions\InvalidIqairConfiguration;
use Illuminate\Support\Facades\Log;
use Throwable;

class IqairCityMeasurementFetcher
{
    public function __construct(private readonly IqairClient $iqair) {}

    public function fetchConfiguredCity(): IqairMeasurement
    {
        $apiKey = config('services.iqair.key');
        $city = config('services.iqair.city');
        $state = config('services.iqair.state');
        $country = config('services.iqair.country');

        if (! $this->hasConfiguration($apiKey, $city, $state, $country)) {
            Log::error('IQAir city measurement fetch failed: missing configuration.', [
                'has_api_key' => is_string($apiKey) && $apiKey !== '',
                'city' => $city,
                'state' => $state,
                'country' => $country,
            ]);

            throw InvalidIqairConfiguration::missing();
        }

        $city = IqairCity::tryFrom($city);
        $state = IqairState::tryFrom($state);
        $country = IqairCountry::tryFrom($country);

        if (! $city || ! $state || ! $country) {
            Log::error('IQAir city measurement fetch failed: unsupported configured location.', [
                'city' => config('services.iqair.city'),
                'state' => config('services.iqair.state'),
                'country' => config('services.iqair.country'),
            ]);

            throw InvalidIqairConfiguration::unsupportedLocation();
        }

        try {
            $measurement = IqairMeasurement::create($this->iqair->fetchCityMeasurement(
                city: $city,
                state: $state,
                country: $country,
                apiKey: $apiKey,
            ));
        } catch (Throwable $exception) {
            Log::error('IQAir city measurement fetch failed.', [
                'city' => $city->value,
                'state' => $state->value,
                'country' => $country->value,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        Log::info('IQAir city measurement saved.', [
            'measurement_id' => $measurement->id,
            'city' => $measurement->city->value,
            'state' => $measurement->state->value,
            'country' => $measurement->country->value,
            'aqius' => $measurement->aqius,
            'pollution_ts' => $measurement->pollution_ts?->toIso8601String(),
            'weather_ts' => $measurement->weather_ts?->toIso8601String(),
        ]);

        return $measurement;
    }

    private function hasConfiguration(mixed ...$values): bool
    {
        foreach ($values as $value) {
            if (! is_string($value) || trim($value) === '') {
                return false;
            }
        }

        return true;
    }
}
