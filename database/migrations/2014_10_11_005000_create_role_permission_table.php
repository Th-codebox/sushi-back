<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {

            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_id');

            $table->string('type')->default(\App\Enums\RolePermissionType::Filials);

            $table->primary(['role_id', 'permission_id']);

            $table->index(['role_id', 'permission_id'],'rp_index');

            $table->foreign('role_id', 'rp_role_id')
                ->on('roles')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('permission_id', 'rp_permission_id_fk')
                ->on('permissions')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropForeign('rp_role_id');
            $table->dropForeign('rp_permission_id_fk');
            $table->drop();
        });
    }
}
