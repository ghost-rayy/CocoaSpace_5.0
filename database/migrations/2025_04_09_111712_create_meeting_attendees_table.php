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
        Schema::create('meeting_attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('name');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('email');
            $table->string('department');
            $table->integer('phone');
            $table->string('meeting_code')->nullable();
            $table->enum('status', ['not_present', 'present'])->default('not_present');
            $table->timestamps();


            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_attendees');
    }
};
