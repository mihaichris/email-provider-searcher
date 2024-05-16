<?php

namespace App\Beecoded;

use Illuminate\Support\Collection;

class ProviderSearchParams extends Collection
{
    /** @return ProviderSearchParams<string, string> */
    public function load(array $filledSearchFields): ProviderSearchParams
    {
        $searchParams = [];
        foreach ($this->items as $searchField) {
            $searchParams[$searchField] = $filledSearchFields[$searchField];
        }

        return new ProviderSearchParams($searchParams);
    }
}
