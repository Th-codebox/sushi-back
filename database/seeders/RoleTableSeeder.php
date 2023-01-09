<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $currentDate = date('Y-m-d H:i:s', time());

        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert([
            'name' => 'Супер админ',
        ], [
            'name'       => 'Супер админ',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert([
            'name' => 'Курьер',
        ], [
            'name'       => 'Курьер',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert([
            'name' => 'Менеджер',
        ], [
            'name'       => 'Менеджер',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert([
            'name' => 'Повар горячего цеха',
        ], [
            'name'       => 'Повар горячего цеха',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert([
            'name' => 'Повар холодного цеха',
        ], [
            'name'       => 'Повар холодного цеха',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert([
            'name' => 'Администратор',
        ], [
            'name'       => 'Администратор',
            'status'     => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
    }
}
