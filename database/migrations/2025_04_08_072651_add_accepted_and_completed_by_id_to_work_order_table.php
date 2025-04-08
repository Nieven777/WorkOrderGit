<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('work_order', function (Blueprint $table) {
        $table->string('accepted_by')->nullable()->after('status'); // store name as string
        $table->unsignedBigInteger('completed_by_id')->nullable()->after('accepted_by');

        $table->foreign('completed_by_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('work_order', function (Blueprint $table) {
        $table->dropForeign(['completed_by_id']);
        $table->dropColumn(['accepted_by', 'completed_by_id']);
    });
}


}; 
