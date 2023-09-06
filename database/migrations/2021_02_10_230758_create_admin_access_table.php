<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_access', function (Blueprint $table) {
            $table->id();
            $table->string('naccess', 100);
            $table->string('archaccess',100);
            $table->string('urlaccess',100);
            $table->string('iconaccess', 100)->nullable();
            $table->string('orden',1)->default(0);
            $table->string('publc',1)->default(1);
            $table->string('princpal',1)->default(1);
            $table->string('groupacc', 100);
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
        Schema::dropIfExists('admin_access');
    }
}
