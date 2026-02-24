<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'alamat' => $data['alamat'],
            'password' => Hash::make($data['password']),
            'role' => 'user', // Role default diproses di sini
        ]);
    }
}