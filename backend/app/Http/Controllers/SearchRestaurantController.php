<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use MeiliSearch\Client;
use MeiliSearch\Endpoints\Indexes;

class SearchRestaurantController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $radius = '_geoRadius(59.9146963, 10.7196681, 100000)';

        return Restaurant::search(
            $request->term,
            function (Indexes $meilisearch, string $query, array $options) use ($radius) {
                $options['filter'] = $radius;

                return $meilisearch->search($query, $options);
            }
        )->get();
    }
}
