<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->unique(); // Unique identifier for the product
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('price', 10, 2); // Decimal with 10 digits in total, 2 after the decimal point
            $table->decimal('retail_price', 10, 2); // Retail price should not change
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->string('brand')->nullable();
            $table->string('size')->nullable();
            $table->decimal('rating_avg', 3, 2)->nullable(); // Average rating
            $table->integer('rating_count')->default(0); // Total rating count
            $table->integer('inventory_count')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
