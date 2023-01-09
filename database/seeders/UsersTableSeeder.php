<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentDate = date('Y-m-d H:i:s', time());


        \Illuminate\Support\Facades\DB::table('users')->updateOrInsert([
            'name' => 'admin',
        ], [
            'name'       => 'admin',
            'phone'      => '79228109900',
            'email'      => 'mail@example.com',
            'password'   => bcrypt('password'),
            'status'     => 1,
            'role_id'    => 1,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);

        \Illuminate\Support\Facades\DB::table('users')->updateOrInsert([
            'name' => 'тестовый курьер',
        ], [
            'name'       => 'тестовый курьер',
            'phone'      => '79999999999',
            'email'      => 'courier@example.com',
            'password'   => bcrypt('courier'),
            'status'     => 1,
            'role_id'    => 2,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ]);
    }
}
