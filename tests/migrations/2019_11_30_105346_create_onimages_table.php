<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnimagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fruits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name');
            $table->timestamps();
        });

        Schema::create('onimages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('attribute');
            $table->string('size');
            $table->text('path');
            $table->integer('width');
            $table->integer('height');
            $table->nullableMorphs('onimagetable');
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
        Schema::dropIfExists('onimages');
        Schema::dropIfExists('fruits');
    }
}
