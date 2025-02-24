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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('provider_id');
            
            $table->json('name'); // translatable 
            $table->json('description'); // translatable

            $table->decimal('discount_price', 10, 2);
            $table->decimal('price', 10, 2);

            $table->enum('price_unit', ['fixed', 'hourly']);
            $table->integer('quantity_unit')->nullable();
            $table->time('duration');

            $table->boolean('featured')->default(0);
            $table->boolean('enable_booking')->default(1);
            $table->boolean('available')->default(1);

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
