<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Meilisearch\Client;

class MeiliSetup extends Command
{
    protected $signature = 'meili:setup';
    protected $description = 'Setup Meilisearch index settings for products';

    public function handle(): int
    {
        $host = config('scout.meilisearch.host');
        $key  = config('scout.meilisearch.key');

        $client = new Client($host, $key);

        $index = $client->index('products');

        // Kereshető mezők (prioritás sorrend)
        $index->updateSearchableAttributes([
            'sku',
            'oem_number',
            'references',
            'name',
            'brand_name',
            'category_name',
        ]);

        // Filterek (Enterprise-ben ez a lényeg)
        $index->updateFilterableAttributes([
            'brand_id',
            'category_id',
            'vehicle_ids',
            'in_stock',
        ]);

        // Rendezhető mezők
        $index->updateSortableAttributes([
            'price_gross',
            'stock_qty',
        ]);

        // Ranking: raktáron lévő előrébb
        $index->updateRankingRules([
            'words',
            'typo',
            'proximity',
            'attribute',
            'sort',
            'exactness',
            'desc(in_stock)',
            'desc(stock_qty)',
        ]);

        $this->info('Meili products index settings updated.');
        return self::SUCCESS;
    }
}
