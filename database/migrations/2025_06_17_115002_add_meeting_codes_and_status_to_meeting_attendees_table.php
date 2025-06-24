<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meeting_attendees', function (Blueprint $table) {
            $table->string('meeting_code')->nullable();
            $table->enum('status', ['not_present', 'present'])->default('not_present');
        });
    }
    
    public function down()
    {
        Schema::table('meeting_attendees', function (Blueprint $table) {
            $table->dropColumn(['meeting_code', 'status']);
        });
    }
};
