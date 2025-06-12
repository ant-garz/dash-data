<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get videos belonging to authenticated user safely
        $user = $request->user();

        // Optional: return empty collection or 403 if no user
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        return $user->videos()->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        // Enforce ownership via VideoPolicy::view()
        $this->authorize('view', $video);

        return $video;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {
        $user = $request->user();
        // Uploaded file
        $file = $request->file('video');

        // Define path: temp/{user_id}/filename.ext
        $path = $file->storeAs(
            "temp/{$user->id}",
            $file->hashName(), // generates a unique filename
            'local' // or another disk like 'temp_uploads' if you defined one
        );

        // Optionally collect fake/placeholder metadata
        $metadata = [
            'codec' => 'H.264',
            'format' => $file->getClientOriginalExtension(),
            'duration' => 60,
            'width' => 1280,
            'height' => 720,
            'size' => $file->getSize(),
        ];

        // Save DB record
        $video = $user->videos()->create([
            'title' => $request->input('title'),
            'filename' => $path, // stored with temp/{user_id}/ prefix
            'recorded_at' => $request->input('recorded_at'),
            ...$metadata,
        ]);

        return response()->json($video, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}
