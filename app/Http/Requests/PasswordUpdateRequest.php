<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Bag terpisah agar pesan tampil di form Ganti Password.
     *
     * @var string
     */
    protected $errorBag = 'password';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Masukkan password saat ini.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required' => 'Masukkan password baru.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Ulangan password baru tidak cocok.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
        ];
    }
}
