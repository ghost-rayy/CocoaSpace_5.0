<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_histories', function (Blueprint $table) {
            $table->string('decline_reason')->nullable()->after('meeting_ended');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_histories', function (Blueprint $table) {
            $table->dropColumn('decline_reason');
        });
    }
};
