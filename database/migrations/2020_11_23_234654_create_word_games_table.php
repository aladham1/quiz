<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('word_games', function (Blueprint $table) {
            $table->id();
            $table->mediumText('question');
            $table->longText('image')->nullable();
            $table->longText('audio')->nullable();
            $table->longText('video')->nullable();
            $table->mediumText('answer');
            $table->bigInteger('order');
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
        Schema::dropIfExists('word_games');
    }
}
