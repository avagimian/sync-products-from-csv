<?php

namespace App\Repositories\Read\Product;

use Illuminate\Support\Collection;

interface ProductReadRepositoryInterface
{
    public function getByHashs(array $hash): Collection;
}
