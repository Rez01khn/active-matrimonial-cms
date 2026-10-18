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
        Schema::table('careers', function (Blueprint $table) {
            if (!Schema::hasColumn('careers', 'profession_status')) {
                $table->string('profession_status')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('careers', 'job_type')) {
                $table->string('job_type')->nullable()->after('profession_status');
            }
            if (!Schema::hasColumn('careers', 'occupation')) {
                $table->string('occupation')->nullable()->after('job_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn(['profession_status', 'job_type', 'occupation']);
        });
    }
};
