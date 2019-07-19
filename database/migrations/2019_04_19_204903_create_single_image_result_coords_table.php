<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSingleImageResultCoordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('single_image_result_coords', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            
            $table->bigIncrements('id');
            $table->bigInteger('single_image_result_id')->unsigned();
            $table->integer('x');
            $table->integer('y');
            $table->boolean('selected');
            $table->double('duration', 7, 2);

            $table->foreign('single_image_result_id')->references('id')->on('single_image_results')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_image_result_coords');
    }
}
