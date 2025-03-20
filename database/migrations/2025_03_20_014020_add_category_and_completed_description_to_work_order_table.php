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
        $table->string('category')->nullable();
        $table->text('completed_description')->nullable();
    });
}

public function down()
{
    Schema::table('work_order', function (Blueprint $table) {
        $table->dropColumn('category');
        $table->dropColumn('completed_description');
    });
}

};
