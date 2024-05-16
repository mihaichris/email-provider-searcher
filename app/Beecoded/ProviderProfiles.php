<?php

namespace App\Beecoded;

use Illuminate\Support\Collection;

class ProviderProfiles extends Collection
{
    public function getEmails(): array
    {
        return $this->map(function (ProviderProfile $profile) {
            return $profile->email;
        })->toArray();
    }
}
