<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuzzlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puzzles', function (Blueprint $table) {
            $table->id();
            $table->mediumText('question');
            $table->longText('description')->nullable();//default("No description provided...");
            //$table->longText('thumb', 65536)->nullable();//default("https://picsum.photos/id/1031/80/80?grayscale&blur=2");
            $table->longText('puzzle_image', 65536)->nullable();//default("No puzzle provided");
            $table->longText('pieces')->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('puzzles');
        Schema::enableForeignKeyConstraints();
    }
}
