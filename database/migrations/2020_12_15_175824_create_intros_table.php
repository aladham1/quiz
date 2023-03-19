<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intros', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->nullable();
            $table->longText('image')->nullable();
            $table->longText('audio')->nullable();
            $table->longText('video')->nullable();
            $table->longText('paragraph')->nullable();
            $table->longText('table')->nullable();
            $table->longText('file')->nullable();
            $table->longText('order_button')->nullable();
            $table->unsignedBigInteger('exam_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intros');
    }
}
