<?php

namespace App\Repositories\Write\Product;

interface ProductWriteRepositoryInterface
{
    public function upsert(array $data);
}
