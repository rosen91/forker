<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use stdClass;

class Restaurant extends Model implements HasMedia
{
    use HasFactory, Searchable, InteractsWithMedia;

    protected $casts = [
        'coordinates' => Point::class
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function searchableAs()
    {
        return 'restaurants_index';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $geo =  [
            'lat' => (float) $this->coordinates->latitude,
            'lng' => (float) $this->coordinates->longitude,
        ];
        info(json_encode($geo));
        return [
            'title' => $this->name,
            'coordinates' => $this->coordinates,
            '_geo' => [
                'lat' => (float) $this->coordinates->latitude,
                'lng' => (float) $this->coordinates->longitude,
            ],
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
