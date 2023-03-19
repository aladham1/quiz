<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->comment('1=video,2=image,3=text,4=bluetooth,5=link,6=Questanya Coupons');
            $table->mediumText('coupon_data');
            $table->boolean('used')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('list_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('list_id')->references('id')->on('coupon_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_lists');
    }
}
