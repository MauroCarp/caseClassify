<?php

namespace App\Filament\Resources\FolderResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use App\Filament\Resources\FolderResource;
use App\Models\Blog\Post;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewFolder extends ViewRecord
{
    protected static string $resource = FolderResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Post */
        $record = $this->getRecord();

        return $record->name;
    }

    protected function getActions(): array
    {
        return [];
    }


    
}
