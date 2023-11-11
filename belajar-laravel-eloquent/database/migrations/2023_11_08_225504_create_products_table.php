<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string("id",100)->nullable(false)->primary();
            $table->string("name")->nullable(false);
            $table->text("description")->nullable();
            $table->integer("price")->nullable(false)->default(0);
            $table->integer("stock")->nullable(false)->default(0);
            $table->string("category_id",100)->nullable(false);
            $table->foreign("category_id")->on("categories")->references("id");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
