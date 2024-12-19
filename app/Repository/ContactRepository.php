<?php

namespace App\Repository;

use App\Models\Contact;
use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContactRepository implements ContactRepositoryInterface
{
    public function createContact(array $data): Contact
    {

        return DB::transaction(function () use ($data) {
            $address = Address::create([
                'address' => $data['address'],
                'complement' => $data['complement'],
                'city' => $data['city'],
                'state' => $data['state'],
                'cep' => $data['cep'],
                'latitude' => isset($data['cordinates']['lat']) ? $data['cordinates']['lat'] : null,
                'longitude' => isset($data['cordinates']['lng']) ? $data['cordinates']['lng'] : null,
            ]);

            return Contact::create([
                'name' => $data['name'],
                'cpf' => $data['cpf'],
                'phone' => $data['phone'],
                'address_id' => $address->id,
                'user_id' => $data['user_id']
            ]);
        });
    }

    public function updateContact(int $id, array $data): Contact
    {
        return DB::transaction(function () use ($id, $data) {
            $contact = Contact::findOrFail($id);

            $contact->address->update([
                'address' => $data['address'],
                'complement' => $data['complement'],
                'city' => $data['city'],
                'state' => $data['state'],
                'cep' => $data['cep'],
                'latitude' => isset($data['cordinates']['lat']) ? $data['cordinates']['lat'] : null,
                'longitude' => isset($data['cordinates']['lng']) ? $data['cordinates']['lng'] : null,
            ]);

            $contact->update([
                'name' => $data['name'],
                'cpf' => $data['cpf'],
                'phone' => $data['phone'],
            ]);
            return $contact;
        });
    }

    public function deleteContact(int $id): mixed
    {
        return DB::transaction(function () use ($id) {
            $contact = Contact::findOrFail($id);
            $contact->delete();

            if ($contact->address->contacts()->count() === 0) {
                $contact->address->delete();
            }
        });
    }

    public function getAllContacts(Request $request): LengthAwarePaginator
    {
        $contacts = Contact::with('address');

        if ($request->filled('name')) {
            $contacts->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('cpf')) {
            $contacts->where('cpf', 'like', '%' . $request->cpf . '%');
        }

        $contacts->orderBy('name', 'asc');
        $perPage = $request->input('per_page', 15);
        return $contacts->paginate($perPage);
    }
}
