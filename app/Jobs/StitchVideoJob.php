<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class StitchVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Video $video) {}

    public function handle(): void
    {
        $segments = $this->video->segments()->orderBy('order')->get();

        $localDisk = Storage::disk('local');
        $tempDir = storage_path("app/tmp/{$this->video->id}");
        $inputListPath = "{$tempDir}/inputs.txt";

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        // Create ffmpeg input file list
        $inputLines = '';
        foreach ($segments as $segment) {
            $absolute = storage_path("app/{$segment->filename}");
            $inputLines .= "file '{$absolute}'\n";
        }

        file_put_contents($inputListPath, $inputLines);

        // Output path
        $outputPath = "user/{$this->video->user_id}/video/{$this->video->id}/compiled.mp4";
        $absoluteOutput = storage_path("app/{$outputPath}");

        // Run ffmpeg
        $process = Process::fromShellCommandline(
            "ffmpeg -f concat -safe 0 -i {$inputListPath} -c copy {$absoluteOutput}"
        );

        $process->run();

        if ($process->isSuccessful()) {
            $this->video->update(['output_filename' => $outputPath]);
        } else {
            logger()->error('Stitching failed: ' . $process->getErrorOutput());
        }
    }
}
