<?php

namespace App\Repositories\Read\Product;

use App\Models\Product\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductReadRepository implements ProductReadRepositoryInterface
{
    private function query(): Builder
    {
        return Product::query();
    }

    public function getByHashs(array $hash): Collection
    {
        return $this->query()->whereIn('md5_hash', $hash)->get();
    }
}
