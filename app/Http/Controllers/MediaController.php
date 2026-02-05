<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Services\StoreMediaService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function App\Helpers\custom_json_response;

class MediaController extends Controller
{
    use AuthorizesRequests;

    protected $storeMediaService;
    public function __construct(StoreMediaService $storeMediaService)
    {
        $this->storeMediaService = $storeMediaService;
    }


    /**
     * Show All Media of the user
     */
    public function index(Request $request)
    {
        $query = Media::where('user_id', Auth::id())
            ->search($request)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $media = $query->map(function ($item) {
            $fileTypes = ['.jpg', '.jpeg', '.png', '.gif'];
            $type = 'document'; // default
            foreach ($fileTypes as $fileType) {
                if (str_contains($item->path, $fileType)) {
                    $type = 'image';
                    break;
                }
            }

            return [
                'id'        => $item->id,
                'type'      => $type,
                'url'       => asset('storage/' . $item->path),
                'filename'  => basename($item->path),
                'size'      => $item->size ?? '',
                'alt'       => $item->alt,
                'title'     => $item->title,
                'caption'   => $item->caption,
                'uploadDate' => $item->created_at->format('Y-m-d H:iA')
            ];
        });


        return custom_json_response(
            $media,
            true,
            'Data Retrieved Successfully',
            200
        );
    }



    /**
     * Store New Media
     */
    public function store(MediaRequest $request)
    {
        $data = $request->validated();

        $storeMedia = $this->storeMediaService->storeMedia($data);

        return custom_json_response(
            $storeMedia,
            true,
            'Media Stored Successfully',
            200,
        );
    }



    /**
     * Update Media Meta-data
     */
    public function update(Request $request, Media $media)
    {
        // abort_if($media->user_id != Auth::id(), 403);
        $this->authorize('update', $media);

        $request->validate([
            'title'     => 'nullable|string|max:255',
            'alt'       => 'nullable|string|max:255',
            'caption'   => 'nullable|string|max:255',
        ]);

        $media->update(
            $request->only(['title', 'alt', 'caption'])
        );

        return custom_json_response(
            $media,
            true,
            'Media Updated Successfully',
            200
        );
    }



    /**
     * Soft-Delete Media
     */
    public function destroy(Media $media)
    {
        // abort_if($media->user_id != Auth::id(), 403);
        $this->authorize('delete', $media);

        $media->delete();

        return custom_json_response(
            $media,
            true,
            'Media Soft Deleted Successfully',
            200
        );
    }
}
