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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('bukti_transfer')->nullable()->after('payment_number');
            $table->unsignedBigInteger('bank_id')->nullable()->after('bukti_transfer');
            $table->text('catatan_admin')->nullable()->after('bank_id');
            
            // Update status enum untuk menambah opsi baru
            $table->dropColumn('status');
        });
        
        // Tambah kembali kolom status dengan enum yang baru
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'waiting_payment', 'waiting_confirmation', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled'])
                  ->default('pending')
                  ->after('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer', 'bank_id', 'catatan_admin']);
            $table->dropColumn('status');
        });
        
        // Kembalikan status enum yang lama
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                  ->default('pending')
                  ->after('total_harga');
        });
    }
};
