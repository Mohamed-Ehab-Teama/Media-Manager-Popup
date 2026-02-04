<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Store New Midea
     */
    public function store(MediaRequest $request)
    {
        $data = $request->validated();
        $file = $data['file'];

        $title = null;
        $alt = $data['alt'] ?? null;
        $caption = $data['caption'] ?? null;
        if ($data['title']) {
            $title = trim(str_replace(['   ', '  ', ' '], '_', $data['title']));
        }

        $path = $file->store('midea/' . now()->format('Y/m/d'), 'public');
        $url = Storage::disk('public')->url($path);

        $width = null;
        $height = null;

        if (str_starts_with($file->getMimeType(), 'image/')) {
            [$width, $height] = getimagesize($file);
        }

        $mideaFile = Media::create([
            'user_id'   => auth()->id(),
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

        return back()->with('success', 'Midea File Uploaded Successfully');
    }
}
