<?php

namespace Database\Seeders;


use App\Enums\CourierOrderStatus;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
  //     $this->call(OrderLatness::class);
       $this->call(PermissionTableSeeder::class);
     //  $this->call(FilialTableSeeder::class);
     //  $this->call(RoleTableSeeder::class);
     //  $this->call(AddPermissionToSuperAdminTableSeeder::class);
     //  $this->call(UsersTableSeeder::class);
     //  $this->call(SettingsTableSeeder::class);
     // $this->call(WorkSpaceTableSeeder::class);
     //  $this->call(PromoCodeTableSeeder::class);
      // $this->call(PolygonTableSeeder::class);
    }
}
