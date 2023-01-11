<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class CitySeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        if (app()->environment() !== 'production') {

            City::truncate();
            $csvData = fopen(base_path('database/csv/worldcities.csv'), 'r');
            $transRow = true;
            while (($data = fgetcsv($csvData, 555, ',')) !== false) {
                if (!$transRow) {
                    City::create([
                        'city' => $data['0'],
                        'city_ascii' => $data['1'],
                        'lat' => $data['2'],
                        'lng' => $data['3'],
                        'country' => $data['4'],
                        'iso2' => $data['5'],
                        'iso3' => $data['6'],
                        'capital' => $data['7'],
                        'population' => $data['9'],
                        'folio' => $data['10'],
                    ]);
                }
                $transRow = false;
            }
            fclose($csvData);

        }

        $this->enableForeignKeys();
    }
}
