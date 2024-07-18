<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Movie extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ensure there are no syntax errors or unexpected function calls here
            $model->id = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'judul_movie', 'gambar','desk', 'tanggal_upload', 'tanggal_rilis', 'jenis_id', 'movie_link', 'duration'
    ];
    protected $appends = ['image_url', 'video_url'];
    public function getImageUrlAttribute()
    {
        return url($this->attributes['gambar']);
    }

    // Accessor for video URL
    public function getVideoUrlAttribute()
    {
        return url($this->attributes['movie_link']);
    }
}
