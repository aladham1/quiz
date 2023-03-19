<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->mediumText('title');
            $table->string('icon')->nullable();
            $table->json('preq')->comment('the type & value of the requirements required for taking the exam');
            $table->boolean('random')->comment("randomize question order \n [0] No/ [1] Yes")->default(false);
            $table->boolean('retake')->comment("[0] No/ [1] Yes")->default(true);
            $table->boolean('chat')->comment("[0] No/ [1] Yes")->default(false);
            $table->boolean('private')->comment("[0] No/ [1] Yes")->default(false);
            $table->decimal('time_limit');
            $table->integer('pass_percentage');
            $table->integer('review_type')->comment("analyse behavior after exam");
            $table->integer('question_count');
            $table->integer('reward_mode');
            $table->integer('reward_type');
            $table->string('coupon_list')->nullable();
            $table->string('hardware_name')->nullable();
            $table->string('special_control_char')->nullable();
            $table->mediumText('reward_message')->nullable();
            $table->string('reward_video')->nullable();
            $table->string('reward_image')->nullable();
            $table->string('cert_lang')->nullable();
            $table->string('sponser')->comment('the sponser logo which will be put on the cert')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
