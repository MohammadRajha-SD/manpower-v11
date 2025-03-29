<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('provider_type_id')->nullable();
            $table->json('description')->nullable();
            $table->string('phone_number');
            $table->string('mobile_number')->nullable();
            $table->decimal('availability_range', 10, 2);
            $table->boolean('available')->default(0);
            $table->boolean('featured')->default(0);
            $table->boolean('accepted')->default(0);
            $table->string('password')->nullable();
            $table->string('confirmation_code')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('stripe_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('provider_type_id')->references('id')->on('provider_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
