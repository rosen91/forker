<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'caption' => $this->caption,
            'rating' => $this->rating,
            'restaurant' => [
                'name' => $this->restaurant?->name,
            ],
            'images' => $this->getMedia('post_images')->map(function (
                Media $media
            ) {
                return [
                    'id' => $media->id,
                    'url' => $media->getFullUrl('thumb'),
                ];
            }),
            'user' => [
                'username' => $this->user->username
            ]
        ];
    }
}
