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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('meeting_room_id');
            $table->foreign('meeting_room_id')->references('id')->on('meeting_rooms')->onDelete('cascade');
            $table->string('requester');
            $table->date('date');
            $table->string('time');
            $table->integer('duration');
            $table->string('extension');
            $table->text('reason');
            $table->integer('capacity');
            $table->string('status')->default('Pending');
            $table->boolean('meeting_ended')->default(false);
            $table->string('e_ticket')->nullable();
            $table->string('flyer_path')->nullable();
            $table->string('decline_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
