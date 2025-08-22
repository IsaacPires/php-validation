<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Credentials extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'                => 'required|email',
            'password'             => 'required|min:6',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'                => 'O campo e-mail é obrigatório.',
            'email.email'                   => 'O e-mail informado não é válido.',
            'password.required'             => 'O campo senha é obrigatório.',
            'password.min'                  => 'A senha deve ter pelo menos 6 caracteres.',
            'g-recaptcha-response.required' => 'O reCAPTCHA é obrigatório.',
            'g-recaptcha-response.captcha'  => 'Falha na verificação do reCAPTCHA.',
        ];
    }
}
