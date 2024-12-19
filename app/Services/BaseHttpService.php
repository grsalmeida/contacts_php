<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Psr\Http\Message\ResponseInterface;

class BaseHttpService
{
    protected string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Envia uma requisição GET para o serviço.
     *
     * @param string $endpoint
     * @param array $query
     * @return array
     */
    protected function get(string $endpoint, array $query = []): array
    {
        $response = Http::get($this->baseUrl . $endpoint, $query);
        if ($response->failed()) {
            throw new \Exception("Request to {$this->baseUrl}{$endpoint} failed.");
        }

        return $response->json();
    }
}
