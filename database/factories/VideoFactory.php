<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        $width = $this->faker->randomElement([1280, 1920]);
        $height = $width === 1280 ? 720 : 1080;
        $format = $this->faker->randomElement(['mp4', 'mov', 'avi']);

        return [
            'title' => $this->faker->sentence,
            'filename' => 'temp/' . $this->faker->randomNumber(2) . '/' . Str::random(10) . ".$format",
            'user_id' => User::factory(), // auto-create related user unless overridden
            'codec' => $this->faker->randomElement(['H.264', 'H.265']),
            'format' => $format,
            'duration' => $this->faker->numberBetween(10, 3600),
            'width' => $width,
            'height' => $height,
            'size' => $this->faker->numberBetween(1_000_000, 500_000_000),
            'recorded_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
