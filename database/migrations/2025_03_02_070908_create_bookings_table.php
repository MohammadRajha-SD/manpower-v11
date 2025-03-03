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
        Schema::create('bookings', function (Blueprint $table) {
            // $table->id();
            // $table->integer('quantity');

            // $table->json('options')->nullable(); 
            // $table->string('coupon')->nullable();
            // $table->boolean('cancel')->default(0);
            // $table->text('hint')->nullable();

            // $table->timestamp('booking_at')->nullable();
            // $table->timestamp('start_at')->nullable();
            // $table->timestamp('ends_at')->nullable();
            // $table->timestamps();

            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            // $table->foreignId('address_id')->nullable()->constrained()->onDelete('set null');
            // $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            // $table->foreignId('booking_status_id')->constrained()->onDelete('cascade');
            // $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            // $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            // $table->foreignId('tax_id')->nullable()->constrained()->onDelete('set null');
        
        
            $table->id();
            $table->json('provider'); 
            $table->json('services');
            $table->json('options')->nullable(); 
            $table->integer('quantity');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('booking_status_id')->constrained()->onDelete('cascade');
            $table->json('address')->nullable(); 
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('coupon')->nullable();
            $table->json('taxes')->nullable(); 
            $table->timestamp('booking_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->text('hint')->nullable();
            $table->boolean('cancel')->default(0);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
