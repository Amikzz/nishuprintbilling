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
        Schema::create('exchange_rates', static function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('currency_from', 3)->default('USD'); // Currency code for the "from" currency (ISO 4217 format)
            $table->string('currency_to', 3)->default('LKR');   // Currency code for the "to" currency (ISO 4217 format)
            $table->decimal('rate', 15, 8);     // The exchange rate, with high precision
            $table->timestamps();               // `created_at` and `updated_at` timestamps
            $table->unique(['currency_from', 'currency_to']); // Ensures no duplicate pairs of exchange rates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
