<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoSegment extends Model
{
    protected $fillable = [
        'video_id',
        'filename',
        'order',
        'duration',
        'start_time',
        'end_time',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
