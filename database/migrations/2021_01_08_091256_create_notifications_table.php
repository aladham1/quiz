<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
			$table->unsignedTinyInteger('type')->comment('0=Exam,1=News,2=Group,3=Recom,4=Link');
			$table->string('title');
			$table->string('body')->nullable();
			$table->string('link')->nullable();
			$table->unsignedBigInteger('notifiable_id')->nullable();
			$table->string('notifiable_type')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
