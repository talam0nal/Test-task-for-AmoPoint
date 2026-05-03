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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency'); // базовая валюта (USD)
            $table->string('target_currency'); // валюта (EUR, GBP...)
            $table->decimal('rate', 15, 6); // курс
            $table->timestamp('fetched_at'); // когда получили
            $table->timestamps();

            $table->unique(['base_currency', 'target_currency']);
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
