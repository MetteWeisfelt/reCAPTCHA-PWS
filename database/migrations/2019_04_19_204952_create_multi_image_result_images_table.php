<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiImageResultImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multi_image_result_images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            
            $table->bigIncrements('id');
            $table->bigInteger('multi_image_result_id')->unsigned();
            $table->string('image_type', 16);
            $table->bigInteger('image_id')->unsigned();
            $table->boolean('selected');
            $table->double('duration', 7, 2);

            $table->foreign('multi_image_result_id')->references('id')->on('multi_image_results')->onDelete('cascade');

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
        Schema::dropIfExists('multi_image_result_images');
    }
}
