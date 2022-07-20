<?php

namespace App\Filament\Resources\RestaurantResource\Pages;

use App\Filament\Resources\RestaurantResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use MatanYadaev\EloquentSpatial\Objects\Point;

class EditRestaurant extends EditRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['coordinates'] = new Point($data['latitude'], $data['longitude']);
        unset($data['latitude']);
        unset($data['longitude']);
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['latitude'] = $data['coordinates']['coordinates'][0];
        $data['longitude'] = $data['coordinates']['coordinates'][1];

        return $data;
    }
}
