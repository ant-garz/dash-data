<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_segments', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID

            $table->uuid('video_id');
            $table->foreign('video_id')->references('id')->on('videos')->constrained()->onDelete('cascade');

            $table->string('filename'); // path to the segment file

            $table->unsignedInteger('order')->default(0); // sequence for stitching

            $table->unsignedInteger('duration')->nullable(); // in seconds
            $table->timestamp('start_time')->nullable(); // optional for trimming
            $table->timestamp('end_time')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_segments');
    }
};
