<?php

namespace App\Repository;

use App\Models\User;

interface UserRepositoryInterface
{
    public function createUser(array $data): User;
    public function deleteUser(int $userId): void;
    public function findUserById(int $userId): User;
}
