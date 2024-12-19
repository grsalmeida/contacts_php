<?php

namespace App\UseCases;

use App\Exceptions\InvalidAddressException;
use App\Repository\ContactRepositoryInterface;
use App\Services\GoogleGeocode\GoogleGeocodeService;
use App\Services\ViaCEP\ViaCepService;
use Illuminate\Http\Request;

class ContactUseCase
{
    private ContactRepositoryInterface $contactRepository;
    private GoogleGeocodeService $googleGeocodeService;
    private ViaCepService $viaCepService;


    public function __construct(
        ContactRepositoryInterface $contactRepository,
        GoogleGeocodeService $googleGeocodeService,
        ViaCepService $viaCepService

    ) {
        $this->contactRepository = $contactRepository;
        $this->googleGeocodeService = $googleGeocodeService;
        $this->viaCepService = $viaCepService;
    }

    public function createContact(array $data, int $userId)
    {
        $data['cordinates'] = $this->googleGeocodeService->getCoordinates($data);
        $data['user_id'] = $userId;
        $contact= $this->contactRepository->createContact($data);
        $contact->load(['address', 'user']);
        return $contact;
    }

    public function updateContact(int $id, array $data)
    {
        $data['cordinates'] = $this->googleGeocodeService->getCoordinates($data);
        $contact = $this->contactRepository->updateContact($id, $data);
        $contact->load(['address', 'user']);
        return $contact;

    }

    public function deleteContact(int $id)
    {
        $this->contactRepository->deleteContact($id);
    }

    public function listContacts(Request $request)
    {
        return $this->contactRepository->getAllContacts($request);
    }

    public function getAddress(Request $request)
    {
        $params = $this->buildAddressQuery($request);
        if (empty($params)) {
            throw new InvalidAddressException('É necessário informar pelo menos um dos seguintes parâmetros: endereço, cidade, estado ou CEP.');
        }

        return $this->viaCepService->getAddress($params);;
    }


    private function buildAddressQuery(Request $request): array
    {
        $params = $request->only(['address', 'city', 'state', 'uf', 'cep']);

        return array_filter($params);
    }
}
