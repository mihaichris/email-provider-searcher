<?php

namespace App\Console\Commands;

use App\Beecoded\Client;
use App\Beecoded\ProviderProfile;
use App\Beecoded\ProviderProfiles;
use App\Beecoded\ProviderSearchParams;
use App\Models\Profile;
use App\Models\Provider;
use App\Models\SearchResult;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class SearchProfiles extends Command
{
    public function __construct(private readonly Client $client)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search Profiles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $providers = Provider::all();
        info('Search profile by criteria:');
        $continue = true;
        while ($continue) {
            $filledSearchableFields = $this->getFilledSearchableFields();
            foreach ($providers as $provider) {
                $providerSearchParams = new ProviderSearchParams(json_decode($provider->search_fields));
                $providerSearchParams = $providerSearchParams->load($filledSearchableFields);
                $profileResults = $this->client->searchProvider($provider->endpoint, $providerSearchParams);
                info('Provider: ' . $provider->endpoint);
                if ($profileResults->isEmpty()) {
                    warning('Empty results');
                    continue;
                }
                $searchResult = new SearchResult();
                $searchResult->provider_id = $provider->id;
                $searchResult->search_params = json_encode($providerSearchParams);
                $searchResult->search_result = json_encode($profileResults);
                $searchResult->save();
                $this->displayResults($profileResults);
                /** @var ProviderProfile $profileResult */
                foreach ($profileResults as $profileResult) {
                    $profile = Profile::where('email', $profileResult->email)->first();
                    if (null === $profile) {
                        $profile = new Profile();
                        $profile->email = $profileResult->email ?? '';
                        $profile->name = $profileResult->name ?? '';
                        $profile->save();
                        info('Saved profile: ' . $profile->email);
                    }
                }
            }
            $continue = confirm('Do you want to perform another search?');
        }
    }

    private function getFilledSearchableFields(): array
    {
        $providersSearchableFields = Provider::getAllSearchableFields();
        $filledSearchableFields = [];
        foreach ($providersSearchableFields as $field) {
            $filledSearchableFields[$field] = text(
                'Provide a ' . $field . ' for searching',
                required: true
            );
        }

        return $filledSearchableFields;
    }

    private function displayResults(ProviderProfiles $profileResults): void
    {
        table(['Emails'], collect($profileResults->getEmails())->map(fn($email) => [$email]));
    }
}
