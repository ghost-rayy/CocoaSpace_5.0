<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_room_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('requester')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('duration')->nullable();
            $table->string('extension')->nullable();
            $table->string('reason')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('status')->nullable();
            $table->string('e_ticket')->nullable();
            $table->boolean('meeting_ended')->default(true);
            $table->timestamps();

            $table->foreign('meeting_room_id')->references('id')->on('meeting_rooms')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_histories');
    }
}
