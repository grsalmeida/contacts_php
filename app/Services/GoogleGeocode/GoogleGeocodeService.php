<?php

namespace App\Services\GoogleGeocode;

use App\Services\BaseHttpService;

class GoogleGeocodeService extends BaseHttpService
{
    private string $apiKey;

    public function __construct()
    {
        parent::__construct(config('geocode.base_url'));
        $this->apiKey = config('geocode.api_key');
    }

    /**
     * Obtem coordenadas a partir de um endereço.
     *
     * @param string $address
     * @return array
     */
    public function getCoordinates(string $address): array
    {
        $query = [
            'address' => $address,
            'key' => $this->apiKey,
        ];

        return $this->get('', $query)['results'][0]['geometry']['location'] ?? [];
    }
}
