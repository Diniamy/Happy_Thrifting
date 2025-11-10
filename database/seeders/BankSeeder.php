<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        Bank::create([
            'nama_bank' => 'Bank BCA',
            'nomor_rekening' => '1234567890',
            'atas_nama' => 'Happy Thrifting Store',
            'is_active' => true
        ]);

        Bank::create([
            'nama_bank' => 'Bank Mandiri',
            'nomor_rekening' => '0987654321',
            'atas_nama' => 'Happy Thrifting Store',
            'is_active' => true
        ]);

        Bank::create([
            'nama_bank' => 'Bank BRI',
            'nomor_rekening' => '5555666677',
            'atas_nama' => 'Happy Thrifting Store',
            'is_active' => true
        ]);
    }
}
