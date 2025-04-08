<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_order', function (Blueprint $table) {
            $table->string('completed_by')->nullable()->after('status'); // Store full name
        });
    }

    public function down(): void
    {
        Schema::table('work_order', function (Blueprint $table) {
            $table->dropColumn('completed_by');
        });
    }
};
