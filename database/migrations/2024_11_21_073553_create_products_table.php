<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('barcode');
            $table->integer('buy_price');
            $table->integer('sell_price');  
            $table->integer('stock');  
            $table->string('image')->nullable();  
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('discount_id')->nullable();  
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discount_products')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
