<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreMediaService
{

    public function storeMedia(array $data,)
    {
        $file = $data['file'];

        $alt = $data['alt'] ?? null;
        $caption = $data['caption'] ?? null;

        $title = isset($data['title']) && $data['title']
            ? trim(str_replace(['   ', '  ', ' '], '_', $data['title']))
            : null;

        // if ($data['title']) {
        //     $title = trim(str_replace(['   ', '  ', ' '], '_', $data['title']));
        // } else {
        //     $title = null;
        // }

        $path = $file->store('Media/' . now()->format('Y/m/d'), 'public');
        $url = Storage::disk('public')->url($path);


        if (str_starts_with($file->getMimeType(), 'image/')) {
            [$width, $height] = getimagesize($file);
        } else {
            $width = null;
            $height = null;
        }

        $MediaFile = Media::create([
            'user_id'   => Auth::id(),
            'title'     => $title ?? pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            ),
            'alt'       => $alt,
            'caption'   => $caption,
            'disk'      => 'public',
            'path'      => $path,
            'url'       => $url,
            'width'     => $width,
            'height'    => $height,
            'mime_type' => $file->getMimeType(),
            'size'      => $file->getSize(),
        ]);

        return $MediaFile;
    }
}
