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
        Schema::create('onimages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('attribute');
            $table->text('path')->comment('Current filesystems drivers when adding this picture');
            $table->string('driver')->comment('Current filesystems drivers when adding this picture');
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
    }
}
