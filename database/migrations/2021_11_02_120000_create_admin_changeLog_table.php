<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminChangeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_changeLog', function (Blueprint $table) {
            $table->id();

            $table->string("user", 200)->nullable();
            $table->string("area", 200)->nullable();
            $table->string("type", 200)->nullable();
            $table->text("comment")->nullable();
            $table->unsignedBigInteger("element_id")->nullable()->add();

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
        Schema::dropIfExists('admin_changeLog');
    }
}
