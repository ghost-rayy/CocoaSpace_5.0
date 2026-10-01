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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('company')->nullable()->after('requester');
            $table->string('department')->nullable()->after('company');
        });

        Schema::table('booking_histories', function (Blueprint $table) {
            $table->string('company')->nullable()->after('requester');
            $table->string('department')->nullable()->after('company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['company', 'department']);
        });

        Schema::table('booking_histories', function (Blueprint $table) {
            $table->dropColumn(['company', 'department']);
        });
    }
};
