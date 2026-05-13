<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mechanics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bengkel_id')->constrained('bengkels')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->text('expertise')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('certification')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'resigned'])->default('aktif');
            $table->text('notes')->nullable();
            $table->date('join_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanics');
    }
};