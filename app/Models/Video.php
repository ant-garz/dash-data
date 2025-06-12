<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'output_filename',
        'codec',
        'format',
        'duration',
        'width',
        'height',
        'size',
        'recorded_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function segments()
    {
        return $this->hasMany(VideoSegment::class);
    }
}
