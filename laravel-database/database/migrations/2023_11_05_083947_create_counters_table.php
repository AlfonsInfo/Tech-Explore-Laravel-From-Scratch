<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //* migrate
    public function up()
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->string('id', 100);
            $table->integer('counter');
            $table->text('desc');
        });
    }

    //* rollback
    public function down()
    {
        Schema::dropIfExists('counters');
    }
};
