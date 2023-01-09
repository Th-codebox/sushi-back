<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorkSpaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentDate = date('Y-m-d H:i:s', time());


        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Курьер №1',
        ], [
            'name'            => 'Курьер №1',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Курьеры',
            'filial_id'       => 1,
            'role_id'         => 2,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Курьер №2',
        ], [
            'name'            => 'Курьер №2',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Курьеры',
            'filial_id'       => 1,
            'role_id'         => 2,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Курьер №3',
        ], [
            'name'            => 'Курьер №3',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Курьеры',
            'filial_id'       => 1,
            'role_id'         => 2,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Курьер №4',
        ], [
            'name'            => 'Курьер №4',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Курьеры',
            'filial_id'       => 1,
            'role_id'         => 2,
            'is_reserve'      => true,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Курьер №5',
        ], [
            'name'            => 'Курьер №5',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Курьеры',
            'filial_id'       => 1,
            'role_id'         => 2,
            'is_reserve'      => true,

        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Курьер №6',
        ], [
            'name'            => 'Курьер №6',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Курьеры',
            'filial_id'       => 1,
            'role_id'         => 2,
            'is_reserve'      => true,
        ]);

        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Менеджер №1',
        ], [
            'name'            => 'Менеджер №1',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Менеджеры',
            'filial_id'       => 1,
            'role_id'         => 3,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Менеджер №2',
        ], [
            'name'            => 'Менеджер №2',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Менеджеры',
            'filial_id'       => 1,
            'role_id'         => 3,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар горячего цеха №1',
        ], [
            'name'            => 'Повар горячего цеха №1',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара горячего цеха',
            'filial_id'       => 1,
            'role_id'         => 4,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар горячего цеха №2',
        ], [
            'name'            => 'Повар горячего цеха №2',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара горячего цеха',
            'filial_id'       => 1,
            'role_id'         => 4,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар горячего цеха №3',
        ], [
            'name'            => 'Повар горячего цеха №3',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара горячего цеха',
            'filial_id'       => 1,
            'role_id'         => 4,
            'is_reserve'      => true,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар горячего цеха №4',
        ], [
            'name'            => 'Повар горячего цеха №4',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара горячего цеха',
            'filial_id'       => 1,
            'role_id'         => 4,
            'is_reserve'      => true,

        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар холоднего цеха цеха №1',
        ], [
            'name'            => 'Повар холоднего цеха цеха №1',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара холоднего цеха',
            'filial_id'       => 1,
            'role_id'         => 5,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар холоднего цеха цеха №2',
        ], [
            'name'            => 'Повар холоднего цеха цеха №2',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара холоднего цеха',
            'filial_id'       => 1,
            'role_id'         => 5,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар холоднего цеха цеха №3',
        ], [
            'name'            => 'Повар холоднего цеха цеха №3',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара холоднего цеха',
            'filial_id'       => 1,
            'role_id'         => 5,
            'is_reserve'      => true,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Повар холоднего цеха цеха №4',
        ], [
            'name'            => 'Повар холоднего цеха цеха №4',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Повара холоднего цеха',
            'filial_id'       => 1,
            'role_id'         => 5,
            'is_reserve'      => true,
        ]);
        \Illuminate\Support\Facades\DB::table('work_spaces')->updateOrInsert([
            'name' => 'Администратор',
        ], [
            'name'            => 'Администратор',
            'additional_info' => '{"phone":"79999999999","code": "test"}',
            'group'           => 'Администраторы',
            'filial_id'       => 1,
            'role_id'         => 6,
        ]);
    }
}
