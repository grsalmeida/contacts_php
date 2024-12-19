<?php

namespace App\Repository;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface ContactRepositoryInterface
{
    public function createContact(array $data): Contact;
    public function updateContact(int $id, array $data): Contact;
    public function deleteContact(int $id): mixed;
    public function getAllContacts(Request $request): LengthAwarePaginator;
}
