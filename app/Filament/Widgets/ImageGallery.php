<?php

namespace App\Filament\Widgets;
use App\Models\Animal; 
use Filament\Widgets\Widget;

class ImageGallery extends Widget
{
    protected static string $view = 'filament.widgets.image-gallery';

    public function getImages()
    {
        
        $record = Animal::find(1);
        return json_decode($record->images);
    }
}
