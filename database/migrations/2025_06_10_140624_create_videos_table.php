<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID

            $table->string('title')->nullable();
            // File path or storage URI
            $table->string('filename');

            // Foreign key to the authenticated user
            $table->uuid('user_id'); // FK is also UUID
            $table->foreign('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');

            // Video metadata
            $table->string('codec')->nullable();              // e.g., "H.264", "H.265"
            $table->string('format')->nullable();          // e.g., "mp4", "mov", "avi"
            $table->unsignedInteger('duration')->nullable();  // in seconds
            $table->unsignedInteger('width')->nullable();     // resolution
            $table->unsignedInteger('height')->nullable();
            $table->unsignedBigInteger('size')->nullable();   // in bytes

            // Timestamps
            $table->timestamp('recorded_at')->nullable();     // from dashcam metadata
            $table->timestamps();                             // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
