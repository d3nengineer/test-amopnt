<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchIqairCityMeasurementTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_a_city_measurement_from_iqair(): void
    {
        $this->configureIqair();

        Http::preventStrayRequests();
        Http::fake([
            'https://api.airvisual.com/v2/city*' => Http::response($this->successfulIqairResponse(), 200),
        ]);

        $this->artisan('iqair:fetch-city')->assertSuccessful();

        $this->assertDatabaseHas('iqair_measurements', [
            'city' => 'Yekaterinburg',
            'state' => 'Sverdlovsk',
            'country' => 'Russia',
            'aqius' => 42,
            'mainus' => 'p2',
            'aqicn' => 21,
            'maincn' => 'p2',
            'temperature' => 17,
            'pressure' => 1012,
            'humidity' => 55,
            'wind_direction' => 180,
            'weather_icon' => '01d',
        ]);

        Http::assertSentCount(1);
    }

    public function test_it_saves_every_poll_even_when_pollution_timestamp_is_duplicated(): void
    {
        $this->configureIqair();

        Http::preventStrayRequests();
        Http::fake([
            'https://api.airvisual.com/v2/city*' => Http::response($this->successfulIqairResponse(), 200),
        ]);

        $this->artisan('iqair:fetch-city')->assertSuccessful();
        $this->artisan('iqair:fetch-city')->assertSuccessful();

        $this->assertDatabaseCount('iqair_measurements', 2);
    }

    public function test_it_fails_without_required_configuration(): void
    {
        config([
            'services.iqair.key' => '',
            'services.iqair.city' => 'Yekaterinburg',
            'services.iqair.state' => 'Sverdlovsk',
            'services.iqair.country' => 'Russia',
        ]);

        Http::preventStrayRequests();

        $this->artisan('iqair:fetch-city')->assertFailed();

        $this->assertDatabaseCount('iqair_measurements', 0);
        Http::assertNothingSent();
    }

    public function test_it_fails_when_iqair_connection_fails(): void
    {
        $this->configureIqair();

        Http::preventStrayRequests();
        Http::fake([
            'https://api.airvisual.com/v2/city*' => Http::failedConnection(),
        ]);

        $this->artisan('iqair:fetch-city')->assertFailed();

        $this->assertDatabaseCount('iqair_measurements', 0);
    }

    public function test_it_fails_when_iqair_returns_an_http_error(): void
    {
        $this->configureIqair();

        Http::preventStrayRequests();
        Http::fake([
            'https://api.airvisual.com/v2/city*' => Http::response(['status' => 'fail'], 500),
        ]);

        $this->artisan('iqair:fetch-city')->assertFailed();

        $this->assertDatabaseCount('iqair_measurements', 0);
    }

    public function test_it_fails_when_iqair_status_is_not_success(): void
    {
        $this->configureIqair();

        Http::preventStrayRequests();
        Http::fake([
            'https://api.airvisual.com/v2/city*' => Http::response([
                'status' => 'fail',
                'data' => [
                    'message' => 'call_limit_reached',
                ],
            ], 200),
        ]);

        $this->artisan('iqair:fetch-city')->assertFailed();

        $this->assertDatabaseCount('iqair_measurements', 0);
    }

    private function configureIqair(): void
    {
        config([
            'services.iqair.key' => 'test-key',
            'services.iqair.city' => 'Yekaterinburg',
            'services.iqair.state' => 'Sverdlovsk',
            'services.iqair.country' => 'Russia',
            'services.iqair.base_url' => 'https://api.airvisual.com/v2',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function successfulIqairResponse(): array
    {
        return [
            'status' => 'success',
            'data' => [
                'city' => 'Yekaterinburg',
                'state' => 'Sverdlovsk',
                'country' => 'Russia',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [60.6122, 56.8519],
                ],
                'current' => [
                    'pollution' => [
                        'ts' => '2026-05-07T10:00:00.000Z',
                        'aqius' => 42,
                        'mainus' => 'p2',
                        'aqicn' => 21,
                        'maincn' => 'p2',
                    ],
                    'weather' => [
                        'ts' => '2026-05-07T10:00:00.000Z',
                        'tp' => 17,
                        'pr' => 1012,
                        'hu' => 55,
                        'ws' => 3.6,
                        'wd' => 180,
                        'ic' => '01d',
                    ],
                ],
            ],
        ];
    }
}
