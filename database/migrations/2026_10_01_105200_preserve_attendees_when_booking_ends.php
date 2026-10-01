<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Keep attendee rows after a booking is moved to history, and remember
     * the original booking id so those attendees can still be found.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('booking_histories', 'booking_id')) {
            Schema::table('booking_histories', function (Blueprint $table) {
                $table->unsignedBigInteger('booking_id')->nullable()->after('id');
            });
        }

        $foreignKey = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'meeting_attendees'
              AND COLUMN_NAME = 'booking_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if ($foreignKey) {
            DB::statement('ALTER TABLE meeting_attendees DROP FOREIGN KEY `'.$foreignKey->CONSTRAINT_NAME.'`');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('booking_histories', 'booking_id')) {
            Schema::table('booking_histories', function (Blueprint $table) {
                $table->dropColumn('booking_id');
            });
        }

        Schema::table('meeting_attendees', function (Blueprint $table) {
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }
};
