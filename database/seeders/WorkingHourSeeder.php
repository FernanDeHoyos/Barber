<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barber;
use App\Models\WorkingHour;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barbers = Barber::all();

        foreach ($barbers as $barber) {

            for ($day = 1; $day <= 5; $day++) {

                WorkingHour::create([
                    'barber_id' => $barber->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00',
                    'active' => true,
                ]);

            }
        }
    }
}
