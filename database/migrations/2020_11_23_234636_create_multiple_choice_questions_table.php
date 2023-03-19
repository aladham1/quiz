<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultipleChoiceQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multiple_choice_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question', 255)->nullable();
            $table->longText('image')->nullable();
            $table->longText('audio')->nullable();
            $table->longText('video')->nullable();
            $table->longText('options');
            $table->string('answer', 4);
            $table->bigInteger('order');
            $table->unsignedBigInteger('exam_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multiple_choice_questions');
    }
}
