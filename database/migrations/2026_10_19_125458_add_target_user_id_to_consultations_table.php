<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->foreignId('target_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->string('meeting_type')->default('matchmaker')->after('target_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['target_user_id']);
            $table->dropColumn(['target_user_id', 'meeting_type']);
        });
    }
};
