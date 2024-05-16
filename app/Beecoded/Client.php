<?php

namespace App\Beecoded;

use Illuminate\Support\Facades\Http;

class Client
{
    private $httpClient;

    public function __construct()
    {
        $loginResponse = Http::post('http://interview-api.stage1.beecoded.ro/auth/login', [
            'email' => 'beecoded@test.com',
            'password' => '0sqwDFe16WTy',
        ]);
        $accessToken = $loginResponse->json('accessToken');
        $this->httpClient = Http::withToken($accessToken)->baseUrl('http://interview-api.stage1.beecoded.ro');
    }

    public function searchProvider(string $providerEndpoint, ProviderSearchParams $providerParams): ProviderProfiles
    {
        $providerResponses = new ProviderProfiles();
        $response = $this->httpClient->get($providerEndpoint, $providerParams);
        if ($response->badRequest()) {
            return $providerResponses;
        }
        if (!empty($response->json('statusCode'))) {
            return $providerResponses;
        }
        foreach ($response->json() as $profile) {
            $providerResponse = new ProviderProfile();
            if (!empty($profile['name'])) {
                $providerResponse->name = $profile['name'];
            }
            if (!empty($profile['email'])) {
                $providerResponse->email = $profile['email'];
            }
            if (!empty($profile['Email'])) {
                $providerResponse->email = $profile['Email'];
            }
            if (is_string($profile)) {
                $providerResponse->email = $profile;
            }
            $providerResponses->add($providerResponse);
        }
        return $providerResponses;
    }
}
