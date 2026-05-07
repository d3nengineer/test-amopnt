<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('iqair_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTimeTz('pollution_ts')->nullable();
            $table->unsignedSmallInteger('aqius')->nullable();
            $table->string('mainus')->nullable();
            $table->unsignedSmallInteger('aqicn')->nullable();
            $table->string('maincn')->nullable();
            $table->dateTimeTz('weather_ts')->nullable();
            $table->smallInteger('temperature')->nullable();
            $table->unsignedSmallInteger('pressure')->nullable();
            $table->unsignedTinyInteger('humidity')->nullable();
            $table->decimal('wind_speed', 8, 2)->nullable();
            $table->unsignedSmallInteger('wind_direction')->nullable();
            $table->string('weather_icon')->nullable();
            $table->timestamps();

            $table->index(['city', 'state', 'country', 'pollution_ts']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iqair_measurements');
    }
};
