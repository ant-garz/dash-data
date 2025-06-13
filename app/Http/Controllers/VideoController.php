<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use FFMpeg\FFProbe;  // Make sure you have php-ffmpeg installed!


use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use App\Models\VideoSegment;
use App\Jobs\StitchVideoJob;

class VideoController extends Controller
{
    use AuthorizesRequests;

    /**
     * List all videos for authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        return $user->videos()->get();
    }

    /**
     * Show a video with segments.
     */
    public function show(Video $video)
    {
        $this->authorize('view', $video);

        $video->load('segments');
        return response()->json($video);
    }

    /**
     * Step 1: Create a base video record with title + recorded_at.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'recorded_at' => 'nullable|date',
        ]);

        $user = $request->user();

        $video = $user->videos()->create([
            'title' => $request->title,
            'recorded_at' => $request->recorded_at,
            'filename' => '', // will update after stitching
        ]);

        return response()->json($video, 201);
    }

    /**
     * Step 2 & 3: Upload segments, save files, extract metadata from first segment,
     * update video record with real metadata.
     */
    public function uploadSegments(Request $request, Video $video)
    {
        $this->authorize('update', $video);

        $request->validate([
            'segments' => 'required|array',
            'segments.*' => 'file|mimes:mp4,mov,avi,webm|max:204800', // 200MB max
        ]);

        $user = $request->user();
        $baseTime = Carbon::now();
        $totalDuration = 0;

        foreach ($request->file('segments') as $index => $file) {
            // Create segment record with placeholder filename (updated after storing)
            $segment = VideoSegment::create([
                'video_id' => $video->id,
                'order' => $index + 1,
                'duration' => null, // will update below
                'start_time' => $baseTime->copy(),
                'end_time' => null,  // will update below
                'filename' => '',
            ]);

            $path = "user/{$user->id}/video/{$video->id}/segments/{$segment->id}.{$file->getClientOriginalExtension()}";

            // Store the file
            Storage::disk('local')->put($path, file_get_contents($file));

            // Extract metadata for this segment using FFprobe
            $fullPath = storage_path("app/{$path}");
            $ffprobe = FFProbe::create();
            $duration = (int) round($ffprobe->format($fullPath)->get('duration'));
            $endTime = $baseTime->copy()->addSeconds($duration);

            // Update segment with real duration, end_time, filename
            $segment->update([
                'duration' => $duration,
                'end_time' => $endTime,
                'filename' => $path,
            ]);

            $baseTime = $endTime;
            $totalDuration += $duration;
        }

        // Extract metadata from the *first* segment for video
        $firstSegment = $video->segments()->orderBy('order')->first();
        if ($firstSegment) {
            $firstPath = storage_path("app/{$firstSegment->filename}");
            $ffprobe = FFProbe::create();

            $format = $ffprobe->format($firstPath);
            $streams = $ffprobe->streams($firstPath);

            // Find video stream info
            $videoStream = $streams->videos()->first();

            $metadata = [
                'codec' => $videoStream ? $videoStream->get('codec_name') : null,
                'format' => $format->get('format_name'),
                'duration' => $totalDuration,
                'width' => $videoStream ? $videoStream->get('width') : null,
                'height' => $videoStream ? $videoStream->get('height') : null,
                'size' => $format->get('size'),
            ];

            $video->update($metadata);
        }

        return response()->json($video->load('segments'), 201);
    }

    /**
     * Step 4: (Optional) Stitch video segments into one final video file.
     * This is a stub method to be implemented.
     */
    public function stitch(Video $video)
    {
        // Authorize user owns the video
        $this->authorize('update', $video);

        // Dispatch the stitching job asynchronously
        StitchVideoJob::dispatch($video);

        // Return immediate response to client
        return response()->json([
            'message' => 'Stitching started. You will be notified when it completes.',
            'video_id' => $video->id,
        ]);
    }

    /**
     * Update video metadata (title, recorded_at, etc).
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        $this->authorize('update', $video);

        $video->update($request->validated());

        return response()->json(['message' => 'Video updated successfully.']);
    }

    /**
     * Delete a video and all related segments.
     */
    public function destroy(Video $video)
    {
        $this->authorize('delete', $video);

        $video->delete();

        return response()->json(['message' => 'Video deleted successfully.']);
    }

    public function cancelUpload(Video $video, Request $request)
    {
        $this->authorize('delete', $video); // Ensure user owns the video

        // Delete associated segments files and records
        foreach ($video->segments as $segment) {
            if ($segment->filename && Storage::disk('local')->exists($segment->filename)) {
                Storage::disk('local')->delete($segment->filename);
            }
            $segment->delete();
        }

        // Delete the main video file if exists
        if ($video->filename && Storage::disk('local')->exists($video->filename)) {
            Storage::disk('local')->delete($video->filename);
        }

        // Finally, delete the video record
        $video->delete();

        return response()->json(['message' => 'Video upload canceled and cleaned up.']);
    }
}
