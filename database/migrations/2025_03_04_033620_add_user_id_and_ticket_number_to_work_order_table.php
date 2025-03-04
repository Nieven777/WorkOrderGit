<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdAndTicketNumberToWorkOrderTable extends Migration
{
    public function up()
    {
        Schema::table('work_order', function (Blueprint $table) {
            // Add user_id column to link the work order to the authenticated user
            $table->unsignedBigInteger('user_id')->after('description');
            
            // Add ticket_number column to store the unique ticket number
            $table->string('ticket_number')->unique()->after('user_id');

            // Add a foreign key constraint for user_id (adjust 'users' table name if needed)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('work_order', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['user_id']);

            // Then drop the added columns
            $table->dropColumn('user_id');
            $table->dropColumn('ticket_number');
        });
    }
}
