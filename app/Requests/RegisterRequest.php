<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Izinkan semua pengguna melakukan registrasi
    }

    public function rules()
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'alamat'   => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
