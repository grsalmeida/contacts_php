<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetContactRequest;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\UseCases\ContactUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private ContactUseCase $contactUseCase;

    public function __construct(ContactUseCase $contactUseCase)
    {
        $this->contactUseCase = $contactUseCase;
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            $contact = $this->contactUseCase->createContact($request->validated(), auth()->id());
            return response()->json([
                'mensagem' => 'Contato criado com sucesso.',
                'contato' => $contact,
            ], 201);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'mensagem' => 'Erro ao criar o contato.',
                'erro' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $contacts = $this->contactUseCase->listContacts($request);
            return response()->json($contacts);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao listar os contatos.',
                'erro' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateContactRequest $request, int $id): JsonResponse
    {
        try {
            $this->contactUseCase->updateContact($id, $request->validated());
            return response()->json(['mensagem' => 'Contato atualizado com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao atualizar o contato.',
                'erro' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->contactUseCase->deleteContact($id);
            return response()->json(['mensagem' => 'Contato excluído com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao excluir o contato.',
                'erro' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAddress(GetContactRequest $request): JsonResponse
    {
        try {
            $address = $this->contactUseCase->getAddress($request);
            return response()->json($address);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao consultar o endereço.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
