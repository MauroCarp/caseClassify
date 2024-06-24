<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Animal extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];
    
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'idFolder', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(150)
              ->height(150 )
              ->sharpen(10);
    }

    public function getImagePathAttribute()
    {
        $gim = $this->gim;
        switch ($gim) {
            case $gim > 6.5:
                $name = 'abundante';
                break;
            case ($gim > 5 && $gim <= 6.5):
                $name = 'moderado';
                break;
            
            case ($gim > 4 && $gim <= 5):
                $name = 'modesto';
                break;
            
            case ($gim > 3 && $gim <= 4):
                $name = 'escaso';
                break;
            
            case ($gim > 1.8 && $gim <= 3):
                $name = 'muyescaso';
                break;

            case ($gim > 0.5 && $gim <= 1.8):
                $name = 'trazas';
                break;

            case ($gim <= 0.5):
                $name = 'singrasa';
                break;
            
            default:
                # code...
                break;
        }
        $path = 'images/marbling/' . $name . '.png';
         
        return $path;
    }

}
