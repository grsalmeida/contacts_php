<?php

namespace App\UseCases;

use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AuthUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Registrar um novo usuário.
     */
    public function register(array $data)
    {
        return $this->userRepository->createUser($data);
    }

    /**
     * Autenticar o usuário.
     */
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return false;
        }

        return Auth::user();
    }

    /**
     * Enviar link de redefinição de senha.
     */
    public function sendResetLink(string $email): bool
    {
        $status = Password::sendResetLink(['email' => $email]);
        return $status == Password::RESET_LINK_SENT;
    }

    /**
     * Excluir conta do usuário.
     */
    public function deleteAccount(int $userId, string $password): bool
    {
        $user = $this->userRepository->findUserById($userId);

        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }

        $this->userRepository->deleteUser($userId);
        return true;
    }
}
