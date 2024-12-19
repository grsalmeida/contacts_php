<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_contact()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'John Doe',
            'cpf' => '93337612091',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'complement' => 'Apt 101',
            'city' => 'São Paulo',
            'state' => 'SP',
            'cep' => '12345678',
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['mensagem' => 'Contato criado com sucesso.']);
    }

    public function test_cpf_must_be_unique()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Contact::create([
            'name' => 'John Doe',
            'cpf' => '93337612091',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'city' => 'São Paulo',
            'state' => 'SP',
            'cep' => '12345678',
            'user_id' => $user->id,
        ]);

        $response = $this->postJson('/api/contacts', [
            'name' => 'Jane Doe',
            'cpf' => '93337612091',
            'phone' => '0987654321',
            'address' => '456 Another St',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'cep' => '98765432',
            'complement' => "teste"
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['message' => 'Este CPF já está cadastrado.']);
    }

    public function test_user_can_list_contacts_with_filters()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Contact::create([
            'name' => 'John Doe',
            'cpf' => '123.456.789-00',
            'phone' => '1234567890',
            'address' => 'Street A',
            'city' => 'São Paulo',
            'state' => 'SP',
            'cep' => '12345-678',
            'user_id' => $user->id,
        ]);

        Contact::create([
            'name' => 'Jane Doe',
            'cpf' => '987.654.321-00',
            'phone' => '9876543210',
            'address' => 'Street B',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'cep' => '98765-432',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/contacts?name=Jane');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Jane Doe']);

        $response = $this->getJson('/api/contacts?cpf=123.456.789-00');
        $response->assertStatus(200);
        $response->assertJsonFragment(['cpf' => '123.456.789-00']);
    }
}
