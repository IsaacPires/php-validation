<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
     public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|string|email|max:255|unique:users,email,' . $this->user?->id,
            'password'             => $this->isMethod('POST') ? 'required|string|min:6|confirmed' : 'sometimes|nullable|string|min:8|confirmed',
            'is_active'            => 'sometimes|boolean',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'O campo nome é obrigatório.',
            'email.required'                 => 'O campo e-mail é obrigatório.',
            'email.email'                    => 'O e-mail informado não é válido.',
            'email.unique'                   => 'Este e-mail já está em uso.',
            'password.required'              => 'O campo senha é obrigatório.',
            'password.min'                   => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'             => 'A confirmação da senha não confere.',
            'g-recaptcha-response.required'  => 'O reCAPTCHA é obrigatório.',
            'g-recaptcha-response.captcha'   => 'Falha na verificação do reCAPTCHA.',
        ];
    }
}