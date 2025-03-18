<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('c_departments', function (Blueprint $table) {
            // Add new college_id column
            $table->unsignedBigInteger('college_id')->after('department')->nullable();

            // Set up foreign key
            $table->foreign('college_id')
                ->references('c_u_id') // This is the primary key in colleges table
                ->on('colleges')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('c_departments', function (Blueprint $table) {
            $table->dropForeign(['college_id']);
            $table->dropColumn('college_id');
        });
    }
};
