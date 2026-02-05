<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $extension = pathinfo($this->path, PATHINFO_EXTENSION);

        $type = in_array(strtolower($extension), $imageExtensions)
            ? 'image'
            : 'document';

        return [
            'id'        => $this->id,
            'type'      => $type,
            'url'       => asset('storage/' . $this->path),
            'filename'  => basename($this->path),
            'size'      => $this->size ?? '',
            'alt'       => $this->alt,
            'title'     => $this->title,
            'caption'   => $this->caption,
            'uploadDate'=> $this->created_at->format('Y-m-d H:i A'),
        ];
    }
}
