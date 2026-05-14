<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_diagnostics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostic_id')->nullable()->constrained('diagnostics')->onDelete('cascade');
            $table->text('symptoms')->nullable();
            $table->longText('ai_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_diagnostics');
    }
};