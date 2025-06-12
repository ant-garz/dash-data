<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Video;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoSegment>
 */
class VideoSegmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $duration = $this->faker->numberBetween(10, 120); // each segment = 10–120s

        return [
            'video_id' => Video::factory(), // can override when seeding
            'filename' => 'temp/' . $this->faker->randomNumber(2) . '/' . Str::random(12) . '.mp4',
            'order' => $this->faker->unique()->numberBetween(1, 10), // will override in loop
            'duration' => $duration,
            'start_time' => null,
            'end_time' => null,
        ];
    }
}
