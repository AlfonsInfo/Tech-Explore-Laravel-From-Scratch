<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //* migrate
        Schema::create('categories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->text('description');
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    //*rollback
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
