<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Str;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    // Ensure 'duration_readable' is included in serialization
    protected $appends = ['duration_readable'];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

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
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function segments()
    {
        return $this->hasMany(VideoSegment::class, 'video_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'id'; // Still UUID, but string-based
    }

    public function getDurationReadableAttribute(): string
    {
        $seconds = $this->duration ?? 0;

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = "{$hours}h";
        }
        if ($minutes > 0 || $hours > 0) {
            $parts[] = "{$minutes}m";
        }
        $parts[] = "{$remainingSeconds}s";

        return implode(' ', $parts);
    }
}
