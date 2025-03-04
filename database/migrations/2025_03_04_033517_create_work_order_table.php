<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrderTable extends Migration
{
    public function up()
    {
        Schema::create('work_order', function (Blueprint $table) {
            $table->id();
            $table->string('requested_by');
            $table->string('requisitioner_type');
            $table->string('other_type')->nullable();
            $table->string('college');
            $table->string('department');
            $table->string('concern');
            $table->string('other_concern')->nullable();
            $table->date('date_requested');
            $table->text('description');
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('work_order');
    }
}
