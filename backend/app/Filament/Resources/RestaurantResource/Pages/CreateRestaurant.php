<?php

namespace App\Filament\Resources\RestaurantResource\Pages;

use App\Filament\Resources\RestaurantResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use MatanYadaev\EloquentSpatial\Objects\Point;

class CreateRestaurant extends CreateRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['coordinates'] = new Point($data['latitude'], $data['longitude']);
        unset($data['latitude']);
        unset($data['longitude']);
        return $data;
    }
}
