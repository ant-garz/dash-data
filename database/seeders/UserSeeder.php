<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoSegment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()
            ->count(5)
            ->create()
            ->each(function (User $user) {
                Video::factory()
                    ->count(rand(2, 4))
                    ->for($user) // properly associates video.user_id = $user->id
                    ->create()
                    ->each(function (Video $video) use ($user) {
                        $segmentCount = rand(5, 10);
                        $totalDuration = 0;

                        for ($i = 1; $i <= $segmentCount; $i++) {
                            $duration = rand(10, 120);

                            $segment = VideoSegment::create([
                                'video_id' => $video->id,
                                'filename' => '', // placeholder
                                'order' => $i,
                                'duration' => $duration,
                            ]);

                            $relativePath = "temp/{$user->id}/{$video->id}/{$segment->id}.mp4";

                            Storage::disk('local')->put($relativePath, "FAKE_VIDEO_SEGMENT_DATA_{$segment->id}");

                            $segment->filename = $relativePath;
                            $segment->save();

                            $totalDuration += $duration;
                        }

                        $video->duration = $totalDuration;
                        $video->save();
                    });
            });
    }
}
