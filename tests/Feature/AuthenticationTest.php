<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends TestCase
{
    /**
     * Test Login with Valid Credentials
     */
    public function test_login_with_valid_creds(): void
    {
        $response = $this->postJson('/api/novos/v1/login', [
            'email' => 'admin@novoslabs.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'email','email_verified_at', 'created_at', 'updated_at'],
                'token',
            ],
        ]);
        
        $this->assertTrue(Auth::check());
    }

    public function test_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/novos/v1/login', [
            'email' => 'admin@no3voslabs.com',
            'password' => '122345678',
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'error' => [
                'message' => 'These credentials do not match our records.',
                'code' => 422
            ],
        ]);
        $this->assertFalse(Auth::check());
    }
}
