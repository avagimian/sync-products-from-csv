<?php

namespace App\Repositories\Write\Product;

use App\Models\Product\Product;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductWriteRepository implements ProductWriteRepositoryInterface
{
    private function query(): Builder
    {
        return Product::query();
    }

    /**
     * @throws Exception
     */
    public function upsert(array $data): bool
    {
        if (!$this->query()->upsert($data, 'id')) {
            throw new Exception();
        }

        return true;
    }
}
