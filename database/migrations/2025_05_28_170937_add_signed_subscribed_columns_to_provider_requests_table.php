<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('provider_requests', function (Blueprint $table) {
            $table->integer('signed')->nullable()->default(0);
            $table->integer('subscribed')->nullable()->default(0);
            // $table->integer('agreement_sent')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('provider_requests', function (Blueprint $table) {
            $table->dropColumn([
                'signed',
                'subscribed',
                // 'agreement_sent'
            ]);
        });
    }
};
