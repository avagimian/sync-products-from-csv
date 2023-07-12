<?php

namespace App\Console\Commands;

use App\Jobs\Product\SaveProductsFromCsvJob;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncProductsCommand extends Command
{
    protected $signature = 'sync:products';
    protected $description = 'Synchronize and save product data from CSV file to database';

    public function handle(): void
    {
        $csvFilePath = 'Products_Sheet1.csv';

        if (!file_exists($csvFilePath)) {
            $this->error("CSV file not found: $csvFilePath");

            return;
        }

        $productsData = $this->parseCSV($csvFilePath);

        $productsData->chunk(500)->each(function ($chunk) {
            SaveProductsFromCsvJob::dispatch($chunk);
        });
    }

    private function parseCSV($filePath): Collection
    {
        $csvData = file($filePath);
        $headers = str_getcsv(array_shift($csvData));

        return collect($csvData)->map(function ($row) use ($headers) {
            $rowData = str_getcsv($row);
            return array_combine($headers, $rowData);
        });
    }
}
