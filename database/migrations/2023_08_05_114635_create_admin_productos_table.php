<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_productos', function (Blueprint $table) {
            $table->id();

            $table->string("nombre", 50)->nullable();
            $table->double("precio", 10)->nullable();
            $table->integer('mixers')->default(0)->nullable();
            $table->foreign('id_tipo')
                ->references('id')->on('admin_tipo_waro')
                ->onDelete('cascade');
            $table->integer('estado')->default(1)->nullable();

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
        Schema::dropIfExists('admin_productos');
    }
}
