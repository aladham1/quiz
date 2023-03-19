<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestIdAttemptCertificateSerialColumnsToExamUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_user', function (Blueprint $table) {
            //$table->string('guest_id')->after('student_id')->nullable();
            $table->unsignedInteger('attempt')->after('questions')->default('1');
            $table->string('cert_serial')->after('attempt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_user', function (Blueprint $table) {
            //$table->dropColumn('guest_id');
            $table->dropColumn('attempt');
            $table->dropColumn('cert_serial');
        });
    }
}
