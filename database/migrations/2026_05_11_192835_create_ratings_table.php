<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('mechanic_id')->constrained('mechanics')->onDelete('cascade');
            $table->integer('service_quality')->default(5);
            $table->integer('professionalism')->default(5);
            $table->integer('timeliness')->default(5);
            $table->integer('overall_rating')->default(5);
            $table->text('review')->nullable();
            $table->boolean('would_recommend')->default(true);
            $table->timestamp('tanggal_rating');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};