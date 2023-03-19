<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestIdAttemptColumnToProjectSubmitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_submits', function (Blueprint $table) {
            //$table->string('guest_id')->after('student_id')->nullable();
            $table->unsignedInteger('attempt')->after('remark_notes')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_submits', function (Blueprint $table) {
            //$table->dropColumn('guest_id');
            $table->dropColumn('attempt');
        });
    }
}
