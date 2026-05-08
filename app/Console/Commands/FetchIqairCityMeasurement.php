<?php

namespace App\Console\Commands;

use App\Services\Iqair\Exceptions\InvalidIqairConfiguration;
use App\Services\Iqair\IqairCityMeasurementFetcher;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('iqair:fetch-city')]
#[Description('Fetch the configured city measurement from IQAir.')]
class FetchIqairCityMeasurement extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(IqairCityMeasurementFetcher $measurements): int
    {
        try {
            $measurements->fetchConfiguredCity();
        } catch (InvalidIqairConfiguration $exception) {
            $this->components->error($exception->getMessage());

            return $this::FAILURE;
        } catch (Throwable) {
            $this->components->error('IQAir measurement was not saved.');

            return $this::FAILURE;
        }

        $this->components->info('IQAir measurement saved.');

        return $this::SUCCESS;
    }
}
