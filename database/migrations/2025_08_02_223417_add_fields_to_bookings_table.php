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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('num_cleaner')->default(1);
            $table->unsignedInteger('stay_hours')->default(1);
            $table->enum('cleaning_times', ['once', 'weekly', 'multiple'])->default('once');
            $table->boolean('cleaning_need')->default(false);
            $table->text('special_insteuctions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'num_cleaner',
                'stay_hours',
                'cleaning_times',
                'cleaning_need',
                'special_insteuctions'
            ]);
        });
    }
};
