<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
          $table->id();
          $table->string('name',40);
          $table->string('usersys',25)->unique();
          $table->string('password',60);
          $table->string('statusUs',1);
          $table->string('mail',50)->nullable();
          $table->unsignedBigInteger('roleUS');
          $table->integer('superuser')->default(0);
          $table->timestamps();
          $table->rememberToken();
          $table->foreign('roleUS')
                ->references('id')->on('admin_roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
