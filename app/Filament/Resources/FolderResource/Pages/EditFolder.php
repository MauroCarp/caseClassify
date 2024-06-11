<?php

namespace App\Filament\Resources\FolderResource\Pages;

use App\Filament\Resources\FolderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFolder extends EditRecord
{
    protected static string $resource = FolderResource::class;

    public function getTitle(): string
    {
        return 'Modificar Carpeta';
    }

    protected function getSaveButtonLabel(): string
    {
        return 'Save Changes';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
