<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->references('id')->on('providers');
            $table->json('search_params');
            $table->json('search_result');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_results');
    }
};
