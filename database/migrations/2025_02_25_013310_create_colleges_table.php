<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('colleges', function (Blueprint $table) {
            $table->id('c_id');
            $table->string('college_name');
            $table->string('college_code');
            $table->string('dep_chair');
            $table->unsignedBigInteger('dep_chair_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_on')->useCurrent();
            $table->integer('receive_concern');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colleges');
    }
};
