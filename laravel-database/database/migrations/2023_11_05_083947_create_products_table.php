<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //* migrate
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('name', 100);
            $table->text('description');
            $table->integer('price')->nullable();
            $table->string('category_id')->index('products_ibfk_1');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    //* rollback    
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
