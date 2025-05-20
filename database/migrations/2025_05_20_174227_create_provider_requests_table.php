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
        Schema::create('provider_requests', function (Blueprint $table) {
            /**
             * company_name
             * company_website
             * contact_person
             * contact_email
             * phone_number
             * number_employees
             * cities
             * services
             * plans
             * notes
             * licence
             */

            $table->id();
            $table->string('company_name');
            $table->string('company_website')->nullable();
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('phone_number');
            $table->integer('number_employees')->nullable();
            $table->json('cities')->nullable();
            $table->json('services')->nullable();
            $table->json('plans')->nullable();
            $table->text('notes')->nullable();
            $table->string('licence')->nullable();
            $table->integer('accepted')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_requests');
    }
};
