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
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('provider_id')->nullable()->constrained('providers')->nullOnDelete();
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_earning', 10, 2)->default(0.00);
            $table->decimal('admin_earning', 10, 2)->default(0.00);
            $table->decimal('provider_earning', 10, 2)->default(0.00);
            $table->decimal('taxes', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earnings');
    }
};
