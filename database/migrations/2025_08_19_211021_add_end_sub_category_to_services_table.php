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
        Schema::table('services', function (Blueprint $table) {
              $table->unsignedBigInteger('end_sub_category')->nullable();

            $table->foreign('end_sub_category')
                  ->references('id')->on('categories')
                  ->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
               $table->dropForeign(['end_sub_category']);
            $table->dropColumn('end_sub_category');
        });
    }
};
