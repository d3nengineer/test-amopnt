<?php

namespace App\Console\Commands;

use App\Models\IqairMeasurement;
use App\Services\Iqair\IqairClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

#[Signature('iqair:fetch-city')]
#[Description('Fetch the configured city measurement from IQAir.')]
class FetchIqairCityMeasurement extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(IqairClient $iqair): int
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

            $this->components->error('IQAir configuration is incomplete.');

            return $this::FAILURE;
        }

        try {
            $measurement = IqairMeasurement::create($iqair->fetchCityMeasurement(
                city: $city,
                state: $state,
                country: $country,
                apiKey: $apiKey,
            ));
        } catch (Throwable $exception) {
            Log::error('IQAir city measurement fetch failed.', [
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            $this->components->error('IQAir measurement was not saved.');

            return $this::FAILURE;
        }

        Log::info('IQAir city measurement saved.', [
            'measurement_id' => $measurement->id,
            'city' => $measurement->city,
            'state' => $measurement->state,
            'country' => $measurement->country,
            'aqius' => $measurement->aqius,
            'pollution_ts' => $measurement->pollution_ts?->toIso8601String(),
            'weather_ts' => $measurement->weather_ts?->toIso8601String(),
        ]);

        $this->components->info('IQAir measurement saved.');

        return $this::SUCCESS;
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
