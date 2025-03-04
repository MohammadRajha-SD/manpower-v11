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
        Schema::create('packs', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_plan_id');
            $table->json('type'); 
            $table->integer('price');
            $table->json('text');
            $table->json('short_description');
            $table->json('description');
            $table->timestamps();
            $table->integer('number_of_months');
            $table->integer('number_of_ads');
            $table->integer('number_of_subcategories');
            $table->boolean('not_in_featured_services')->default(false);
            $table->boolean('not_on_image_slider')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packs');
    }
};
