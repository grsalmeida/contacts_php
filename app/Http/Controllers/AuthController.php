<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\UseCases\AuthUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthUseCase $authUseCase;

    public function __construct(AuthUseCase $authUseCase)
    {
        $this->authUseCase = $authUseCase;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authUseCase->register($request->validated());
            return response()->json([
                'mensagem' => 'Usuário registrado com sucesso.',
                'usuario' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao registrar o usuário.',
                'erro' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authUseCase->login($request->validated());

        if (!$user) {
            return response()->json(['mensagem' => 'Credenciais inválidas.'], 401);
        }
        $token = $request->user()->createToken($request->password);

        return response()->json([
            'mensagem' => 'Login realizado com sucesso.',
            'token' => $token->plainTextToken,
        ], 200);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $status = $this->authUseCase->sendResetLink($request->input('email'));

            return $status
                ? response()->json(['mensagem' => 'Link de redefinição de senha enviado para o seu e-mail.'])
                : response()->json(['mensagem' => 'Não foi possível enviar o link de redefinição de senha.'], 500);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao enviar o link de redefinição de senha.',
                'erro' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAccount(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $deleted = $this->authUseCase->deleteAccount(auth()->id(), $request->input('password'));

        if (!$deleted) {
            return response()->json(['mensagem' => 'Senha incorreta.'], 401);
        }

        return response()->json(['mensagem' => 'Conta excluída com sucesso.'], 200);
    }
}
