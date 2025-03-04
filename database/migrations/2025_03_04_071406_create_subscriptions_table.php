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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 8, 2)->default(0);
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pack_id');
            $table->string('status')->nullable();
            $table->string('name');
            $table->string('stripe_id')->nullable();
            $table->string('stripe_status')->nullable();
            $table->string('stripe_plan')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints if applicable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pack_id')->references('id')->on('packs')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
