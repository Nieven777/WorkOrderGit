<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToWorkOrderTable extends Migration
{
    public function up()
    {
        Schema::table('work_order', function (Blueprint $table) {
            // Add the status column after ticket_number with a default value of 'Submitted'
            $table->enum('status', ['Submitted', 'Received', 'Completed', 'Canceled'])
                  ->default('Submitted')
                  ->after('ticket_number');
        });
    }

    public function down()
    {
        Schema::table('work_order', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
