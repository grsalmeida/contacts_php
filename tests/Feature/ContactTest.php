<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_contact()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $data = [
            'name' => 'John Doe',
            'cpf' => '123.456.789-00',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'complement' => 'Apt 101',
            'latitude' => -23.5505,
            'longitude' => -46.6333,
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['mensagem' => 'Contact created successfully']);
    }

    public function test_cpf_must_be_unique()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        Contact::create([
            'name' => 'John Doe',
            'cpf' => '123.456.789-00',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'latitude' => -23.5505,
            'longitude' => -46.6333,
            'user_id' => $user->id
        ]);

        $response = $this->postJson('/api/contacts', [
            'name' => 'Jane Doe',
            'cpf' => '123.456.789-00',
            'phone' => '0987654321',
            'address' => '456 Another St',
            'latitude' => -23.5505,
            'longitude' => -46.6333,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['cpf' => ['The cpf has already been taken']]);
    }

    public function test_user_can_list_contacts_with_filters()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Criar alguns contatos
        Contact::create([
            'name' => 'John Doe',
            'cpf' => '123.456.789-00',
            'phone' => '1234567890',
            'address' => 'Street A',
            'latitude' => -23.5505,
            'longitude' => -46.6333,
            'user_id' => $user->id
        ]);

        Contact::create([
            'name' => 'Jane Doe',
            'cpf' => '987.654.321-00',
            'phone' => '9876543210',
            'address' => 'Street B',
            'latitude' => -23.5505,
            'longitude' => -46.6333,
            'user_id' => $user->id
        ]);

        // Testar filtro por nome
        $response = $this->getJson('/api/contacts?name=Jane');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Jane Doe']);

        // Testar filtro por CPF
        $response = $this->getJson('/api/contacts?cpf=123.456.789-00');
        $response->assertStatus(200);
        $response->assertJsonFragment(['cpf' => '123.456.789-00']);
    }
}

