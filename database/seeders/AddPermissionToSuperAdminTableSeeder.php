<?php

namespace Database\Seeders;

use App\Enums\RolePermissionType;
use App\Models\System\Role;
use Illuminate\Database\Seeder;

class AddPermissionToSuperAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = \Illuminate\Support\Facades\DB::table('permissions')->get()->toArray();
        $role = (new  Role())->where('name', '=', 'Супер админ')->firstOrFail();

        foreach ($permissions as $permission) {

            $role->permissions()->attach($permission->id, ['type' => RolePermissionType::All]);
        }


    }
}
