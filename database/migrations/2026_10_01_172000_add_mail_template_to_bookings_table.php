<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('email_subject')->nullable()->after('flyer_path');
            $table->longText('email_body')->nullable()->after('email_subject');
            $table->json('email_attachment_paths')->nullable()->after('email_body');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['email_subject', 'email_body', 'email_attachment_paths']);
        });
    }
};
