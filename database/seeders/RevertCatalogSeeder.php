<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RevertCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Vacía la tabla y reinicia el contador ID (repite por cada tabla)

            DB::table('batches')->truncate(); 
            DB::table('operations')->truncate(); 
            DB::table('processes')->truncate(); 
            DB::table('process_routes')->truncate(); 
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
