<?php

namespace App\Services\Iqair;

use App\Enums\IqairCity;
use App\Enums\IqairCountry;
use App\Enums\IqairState;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Arr;
use RuntimeException;
use UnexpectedValueException;

class IqairClient
{
    public function __construct(private readonly Factory $http) {}

    /**
     * Fetch and normalize city measurements from IQAir.
     *
     * @return array{
     *     city: IqairCity,
     *     state: IqairState,
     *     country: IqairCountry,
     *     latitude: float|null,
     *     longitude: float|null,
     *     pollution_ts: CarbonImmutable|null,
     *     aqius: int|null,
     *     mainus: string|null,
     *     aqicn: int|null,
     *     maincn: string|null,
     *     weather_ts: CarbonImmutable|null,
     *     temperature: int|null,
     *     pressure: int|null,
     *     humidity: int|null,
     *     wind_speed: float|null,
     *     wind_direction: int|null,
     *     weather_icon: string|null
     * }
     */
    public function fetchCityMeasurement(IqairCity $city, IqairState $state, IqairCountry $country, string $apiKey): array
    {
        $response = $this->http
            ->baseUrl((string) config('services.iqair.base_url', 'https://api.airvisual.com/v2'))
            ->connectTimeout(5)
            ->timeout(10)
            ->retry([100, 250, 500])
            ->get('/city', [
                'city' => $city->value,
                'state' => $state->value,
                'country' => $country->value,
                'key' => $apiKey,
            ])
            ->throw()
            ->json();

        if (! is_array($response)) {
            throw new UnexpectedValueException('IQAir returned a malformed response.');
        }

        if (Arr::get($response, 'status') !== 'success') {
            throw new RuntimeException('IQAir returned an unsuccessful status.');
        }

        $data = Arr::get($response, 'data');

        if (! is_array($data)) {
            throw new UnexpectedValueException('IQAir response is missing data.');
        }

        $coordinates = Arr::get($data, 'location.coordinates', []);

        return [
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'latitude' => $this->coordinate($coordinates, 1),
            'longitude' => $this->coordinate($coordinates, 0),
            'pollution_ts' => $this->timestamp(Arr::get($data, 'current.pollution.ts')),
            'aqius' => $this->integer(Arr::get($data, 'current.pollution.aqius')),
            'mainus' => $this->string(Arr::get($data, 'current.pollution.mainus')),
            'aqicn' => $this->integer(Arr::get($data, 'current.pollution.aqicn')),
            'maincn' => $this->string(Arr::get($data, 'current.pollution.maincn')),
            'weather_ts' => $this->timestamp(Arr::get($data, 'current.weather.ts')),
            'temperature' => $this->integer(Arr::get($data, 'current.weather.tp')),
            'pressure' => $this->integer(Arr::get($data, 'current.weather.pr')),
            'humidity' => $this->integer(Arr::get($data, 'current.weather.hu')),
            'wind_speed' => $this->float(Arr::get($data, 'current.weather.ws')),
            'wind_direction' => $this->integer(Arr::get($data, 'current.weather.wd')),
            'weather_icon' => $this->string(Arr::get($data, 'current.weather.ic')),
        ];
    }

    /**
     * @param  array<int, mixed>  $coordinates
     */
    private function coordinate(array $coordinates, int $index): ?float
    {
        return $this->float($coordinates[$index] ?? null);
    }

    private function float(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private function integer(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function string(mixed $value): ?string
    {
        return is_scalar($value) ? (string) $value : null;
    }

    private function timestamp(mixed $value): ?CarbonImmutable
    {
        return is_string($value) && $value !== '' ? CarbonImmutable::parse($value) : null;
    }
}
