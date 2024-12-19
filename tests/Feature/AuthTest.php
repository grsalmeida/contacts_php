<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['mensagem', 'usuario']);
    }

    public function test_login()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['mensagem', 'token']);
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/account', ['password' => 'incorrect-password']);
        $response->assertStatus(401);
        $response->assertJson(['mensagem' => 'Senha incorreta.']);

        $response = $this->deleteJson('/api/account', ['password' => 'correct-password']);
        $response->assertStatus(200);
        $response->assertJson(['mensagem' => 'Account deleted successfully']);
    }
}
