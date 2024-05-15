<?php

namespace Database\Seeders;

use App\Models\Provider;
use Database\Factories\ProviderFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIyIiwiaWF0IjoxNzAyMzk3NzAwLCJleHAiOjE3MDI0ODQxMDB9.8ZXXcEo1jMvJ3i6xC-n4XxCYPPlAVf1hYQNZbkk5yhM';
        Provider::factory()->createMany([
            [
                'search_fields' => json_encode([
                    'name',
                    'company'
                ]),
                'endpoint' => '/mock/provider1/email',
                'api_key' => $apiKey
            ],
            [
                'search_fields' => json_encode([
                    'linkedInProfileUrl',
                ]),
                'endpoint' => '/mock/provider2/email',
                'api_key' => $apiKey
            ],
            [
                'search_fields' => json_encode([
                    'linkedInProfileUrl',
                    'company'
                ]),
                'endpoint' => '/mock/provider3/email',
                'api_key' => $apiKey
            ]
        ]);
    }
}
