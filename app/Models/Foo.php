<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Foo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $table = 'foos';

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'name' => 'string'
    ];

    public function registerMediaCollections(): void
    {
        // use disk ( bcupr ) for private attachements
        $this
            ->addMediaCollection('image')
            ->singleFile()->useDisk('bcupu')->acceptsMimeTypes(config('app.mime_types'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
