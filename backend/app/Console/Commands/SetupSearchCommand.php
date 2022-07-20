<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Illuminate\Console\Command;
use MeiliSearch\Client;

class SetupSearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds geo field to Meilisearch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client(config('scout.meilisearch.host'));
        $client->deleteIndex('restaurants_index');
        $client->createIndex('restaurants_index');
        Restaurant::all()->searchable();
        $client->index('restaurants_index')->updateFilterableAttributes(['_geo']);
        $client->index('restaurants_index')->updateSortableAttributes(['_geo']);
    }
}
