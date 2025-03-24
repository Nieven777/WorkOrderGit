<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToWorkOrderTable extends Migration
{
    public function up()
    {
        Schema::table('work_order', function (Blueprint $table) {
            $table->string('category')->nullable()->after('status');
            $table->text('completed_description')->nullable()->after('category');
            // received_by will store the id of the user that accepted the order.
            $table->unsignedBigInteger('received_by')->nullable()->after('completed_description');

            // Optional: If you want a foreign key constraint (assuming your users table primary key is id)
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('work_order', function (Blueprint $table) {
            $table->dropForeign(['received_by']);
            $table->dropColumn('category');
            $table->dropColumn('completed_description');
            $table->dropColumn('received_by');
        });
    }
}

