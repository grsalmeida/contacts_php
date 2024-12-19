<?php

namespace App\Services\ViaCEP;

use App\Services\BaseHttpService;

class ViaCepService extends BaseHttpService
{
    public function __construct()
    {
        parent::__construct(config('viacep.base_url'));
    }

    /**
     * Obtem o endereço completo a partir de um CEP.
     *
     * @param string $cep
     * @return array
     */
    public function getAddress(array $data)
    {
        if (isset($data['cep'])) {

            $cep = $this->sanitizeCep($data['cep']);
            return $this->get("{$cep}/json/");
        }
        if (isset($data['uf']) && isset($data['city']) && isset($data['address'])) {
            $encodedAddress = urlencode($data['address']);
            $encodedCity = urlencode($data['city']);
            $encodedUf = urlencode($data['uf']);
            return $this->get("{$encodedUf}/{$encodedCity}/{$encodedAddress}/json/");
        }
    }

    /**
     * Sanitize o CEP para garantir que ele tenha apenas números.
     *
     * @param string $cep
     * @return string
     */
    private function sanitizeCep(string $cep): string
    {
        return preg_replace('/\D/', '', $cep);
    }
}
