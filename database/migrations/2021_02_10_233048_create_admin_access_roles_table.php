<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAccessRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_access_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_role");
            $table->unsignedBigInteger("id_access");
            $table->foreign('id_role')
                  ->references('id')->on('admin_roles')
                  ->onDelete('cascade');
            $table->foreign('id_access')
                  ->references('id')->on('admin_access')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_access_roles');
    }
}
