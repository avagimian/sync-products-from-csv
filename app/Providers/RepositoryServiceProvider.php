<?php

namespace App\Providers;

use App\Repositories\Read\Product\ProductReadRepository;
use App\Repositories\Read\Product\ProductReadRepositoryInterface;
use App\Repositories\Write\Product\ProductWriteRepository;
use App\Repositories\Write\Product\ProductWriteRepositoryInterface;

class RepositoryServiceProvider extends AppServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProductWriteRepositoryInterface::class,
            ProductWriteRepository::class
        );

        $this->app->bind(
            ProductReadRepositoryInterface::class,
            ProductReadRepository::class
        );
    }
}
