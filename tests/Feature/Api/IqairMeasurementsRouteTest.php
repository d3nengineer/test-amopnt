<?php

namespace Tests\Feature\Api;

use App\Enums\IqairCity;
use App\Enums\IqairCountry;
use App\Enums\IqairState;
use App\Models\IqairMeasurement;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IqairMeasurementsRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_iqair_measurements_as_a_json_array(): void
    {
        $olderMeasurement = IqairMeasurement::factory()->create([
            'city' => IqairCity::Yekaterinburg,
            'state' => IqairState::Sverdlovsk,
            'country' => IqairCountry::Russia,
            'latitude' => 56.8519,
            'longitude' => 60.6122,
            'pollution_ts' => CarbonImmutable::parse('2026-05-07 10:00:00', 'UTC'),
            'aqius' => 42,
            'mainus' => 'p2',
            'aqicn' => 21,
            'maincn' => 'p2',
            'weather_ts' => CarbonImmutable::parse('2026-05-07 10:05:00', 'UTC'),
            'temperature' => 17,
            'pressure' => 1012,
            'humidity' => 55,
            'wind_speed' => 3.6,
            'wind_direction' => 180,
            'weather_icon' => '01d',
        ]);

        $latestMeasurement = IqairMeasurement::factory()->create([
            'city' => IqairCity::Perm,
            'state' => IqairState::PermKrai,
            'country' => IqairCountry::Russia,
            'latitude' => null,
            'longitude' => null,
            'pollution_ts' => null,
            'aqius' => 15,
            'mainus' => 'p1',
            'aqicn' => null,
            'maincn' => null,
            'weather_ts' => null,
            'temperature' => -4,
            'pressure' => 1001,
            'humidity' => 70,
            'wind_speed' => null,
            'wind_direction' => null,
            'weather_icon' => null,
        ]);

        $response = $this->getJson('/api/iqair-measurements');

        $response->assertOk();
        $this->assertStringStartsWith('application/json', (string) $response->headers->get('content-type'));

        $payload = $response->json();

        $this->assertIsArray($payload);
        $this->assertArrayNotHasKey('data', $payload);
        $this->assertCount(2, $payload);
        $this->assertSame($latestMeasurement->id, $payload[0]['id']);
        $this->assertSame($olderMeasurement->id, $payload[1]['id']);
        $this->assertSame($this->expectedMeasurementKeys(), array_keys($payload[0]));

        $this->assertSame(IqairCity::Perm->value, $payload[0]['city']);
        $this->assertSame(IqairState::PermKrai->value, $payload[0]['state']);
        $this->assertSame(IqairCountry::Russia->value, $payload[0]['country']);
        $this->assertNull($payload[0]['latitude']);
        $this->assertNull($payload[0]['pollution_ts']);
        $this->assertSame(15, $payload[0]['aqius']);
        $this->assertNull($payload[0]['aqicn']);
        $this->assertNull($payload[0]['wind_speed']);
        $this->assertNull($payload[0]['weather_icon']);

        $this->assertSame(IqairCity::Yekaterinburg->value, $payload[1]['city']);
        $this->assertSame(56.8519, $payload[1]['latitude']);
        $this->assertSame(60.6122, $payload[1]['longitude']);
        $this->assertSame('2026-05-07T10:00:00+00:00', $payload[1]['pollution_ts']);
        $this->assertSame('2026-05-07T10:05:00+00:00', $payload[1]['weather_ts']);
        $this->assertSame(3.6, $payload[1]['wind_speed']);
        $this->assertArrayNotHasKey('created_at', $payload[1]);
        $this->assertArrayNotHasKey('updated_at', $payload[1]);
    }

    public function test_it_returns_an_empty_json_array_when_no_measurements_exist(): void
    {
        $response = $this->getJson('/api/iqair-measurements');

        $response
            ->assertOk()
            ->assertExactJson([]);
    }

    /**
     * @return list<string>
     */
    private function expectedMeasurementKeys(): array
    {
        return [
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
        ];
    }
}
