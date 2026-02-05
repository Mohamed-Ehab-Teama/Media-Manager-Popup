<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Services\StoreMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
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
        $media = Media::where('user_id', Auth::id())
            ->search($request)
            ->paginate(20);

        return view('', compact('media'));
    }



    /**
     * Store New Media
     */
    public function store(MediaRequest $request)
    {
        $data = $request->validated();

        $storeMedia = $this->storeMediaService->storeMedia($data);

        return back()->with('success', 'Media File Uploaded Successfully');
    }
}
