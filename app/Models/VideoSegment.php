<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id'; // Only if you're still using the 'id' column but it's a UUID

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'id'; // Still UUID, but string-based
    }
}
