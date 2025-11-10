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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID unik untuk order
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->decimal('total_harga', 10, 2)->nullable(); // Total harga semua item
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending'); // Status order
            $table->string('payment_name')->nullable();     // Nama bank atau metode pembayaran
            $table->string('payment_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
