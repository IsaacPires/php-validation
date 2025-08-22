<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function loginValid(): void
    {
        $this->mockCaptchaValidation();

        $password = '123456';

        User::factory()->create([
            'email' => 'test@test.com',
            'active'=> true,
            'password' => Hash::make($password),
        ]);

        $credentials = [
            'email' => 'test@test.com',
            'password' => $password,
            'g-recaptcha-response' => 'fake-recaptcha-token',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
                'token',
            ]);
    }

    /**
     * @test
     */
    public function loginInvalid(): void
    {
        $this->mockCaptchaValidation();

        User::factory()->create([
            'email' => 'test@test.com',
            'active'=> true,
            'password' => Hash::make('123456'),
        ]);

        $credentials = [
            'email' => 'test@fake.com',
            'password' => 'wrong-password',
            'g-recaptcha-response' => 'fake-recaptcha-token',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'As credenciais fornecidas estão incorretas.', 
            ]);
    }

    protected function mockCaptchaValidation()
    {
        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            return true;
        });
    }
}