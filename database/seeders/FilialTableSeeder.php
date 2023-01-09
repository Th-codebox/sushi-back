<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FilialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentDate = date('Y-m-d H:i:s', time());


        \Illuminate\Support\Facades\DB::table('filials')->updateOrInsert([
            'name' => 'СушиФокс Калининский',
        ], [
            'name'           => 'СушиФокс Калининский',
            'city'           => 'city',
            'requisites'     => 'requisites',
            'address'        => 'address',
            'min_order_cost' => 500,
            'status'         => 1,
            'created_at'     => $currentDate,
            'updated_at'     => $currentDate,
        ]);
    }
}
