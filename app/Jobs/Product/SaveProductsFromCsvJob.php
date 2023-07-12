<?php

namespace App\Jobs\Product;

use App\Repositories\Read\Product\ProductReadRepositoryInterface;
use App\Repositories\Write\Product\ProductWriteRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SaveProductsFromCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductReadRepositoryInterface $productReadRepository;
    private Collection $values;
    private Collection $result;
    private int $count;

    public function __construct(protected Collection $chunk)
    {
    }

    public function handle(
        ProductWriteRepositoryInterface $productWriteRepository,
        ProductReadRepositoryInterface $productReadRepository
    ): void {
        $this->init($productReadRepository);

        foreach ($this->chunk as $product) {
            $product['price'] = str_replace(',', '.', $product['price']);
            $product['md5_hash'] = md5(implode(':', array_values($product)));
            $product['id'] = (int)$product['id'];
            $this->values->push($product);
        }

        $this->determineDataDifference($this->values);

        if ($this->count !== 0) {
            $productWriteRepository->upsert($this->result->toArray());
        }

        Log::info("$this->count products synchronized successfully!");
    }

    private function init(ProductReadRepositoryInterface $productReadRepository): void
    {
        $this->productReadRepository = $productReadRepository;
        $this->values = collect();
    }

    private function determineDataDifference(Collection $values): void
    {
        $md5Hashes = $values->pluck('md5_hash');
        $hashes = $this->productReadRepository->getByHashs($md5Hashes->toArray())
            ->pluck('md5_hash');
        $diff = $md5Hashes->diff($hashes);
        $this->result = $values->whereIn('md5_hash', $diff);
        $this->count = $diff->count();
    }
}
