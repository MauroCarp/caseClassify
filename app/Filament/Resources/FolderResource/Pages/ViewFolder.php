<?php

namespace App\Filament\Resources\FolderResource\Pages;

use App\Filament\Resources\AnimalResource\Widgets\AnimalFolderTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\FolderResource;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Animal;
use App\Models\Folder;
use Filament\Tables;
use Filament\Tables\Table;

class ViewFolder extends ViewRecord 
{
    protected static string $resource = FolderResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Folder $record */
        $record = $this->getRecord();

        return $record->name;
    }

    
    protected function getHeaderWidgets(): array
    {

        /** @var Folder $record */
        $record = $this->getRecord();

        return [
            AnimalFolderTable::make([
                'idFolder' => $record->id,
            ])
        ];
    }
}
