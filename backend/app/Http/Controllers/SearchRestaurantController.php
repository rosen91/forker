<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use MeiliSearch\Client;
use MeiliSearch\Endpoints\Indexes;
use SKAgarwal\GoogleApi;
use SKAgarwal\GoogleApi\PlacesApi;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;

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

        $radius = null;
        if ($request->latitude && $request->longitude) {
            $radius = '_geoRadius(' . $request->latitude . ', ' . $request->longitude . ', 100)';
            $sort = ['_geoPoint(' . $request->latitude . ', ' . $request->longitude . '):asc'];
        }

        $searchResults = Restaurant::search(
            $request->term ?? "",
            function (Indexes $meilisearch, string $query, array $options) use ($radius, $sort) {
                if ($radius) {
                    $options['filter'] = $radius;
                    $options['sort'] = $sort;
                }

                return $meilisearch->search($query, $options);
            }
        )->get();

        if ($searchResults->count() === 0) {
            $places = new PlacesApi(config('google.places.key'));
            $places = $places->nearbySearch($request->latitude . ',' . $request->longitude, "", ['keyword' => $request->term ?? "", 'type' => 'restaurant', 'rankby' => 'distance']);
            foreach ($places['results'] as $place) {
                $address = Str::before($place['vicinity'], ',');
                if (Restaurant::where('name', $place['name'])->where('address', $address)->exists()) {
                    continue;
                }
                $restaurant = new Restaurant;
                $restaurant->name = $place['name'];
                $restaurant->address = $address;
                $restaurant->city = Str::after($place['vicinity'], ',');
                $restaurant->coordinates = new Point($place['geometry']['location']['lat'], $place['geometry']['location']['lng']);
                $restaurant->rating = $place['rating'] ?? null;
                $restaurant->country = Str::after($place['plus_code']['compound_code'], ',');
                $restaurant->save();
                $searchResults->append($restaurant);
            }
        }

        return response()->json($searchResults);
    }
}
