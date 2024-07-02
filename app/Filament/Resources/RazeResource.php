<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RazeResource\Pages;
use App\Filament\Resources\RazeResource\RelationManagers;
use App\Models\Raze;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

class RazeResource extends Resource
{
    protected static ?string $model = Raze::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationLabel = 'Razas'; // Cambiar el texto en el menú de navegación

    protected static ?string $pluralModelLabel = 'Razas';

    protected static ?string $modelLabel = 'Raza';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish'
        ];
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()->hasRole(['super_admin','admin']    );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRazes::route('/'),
            'create' => Pages\CreateRaze::route('/create'),
            'edit' => Pages\EditRaze::route('/{record}/edit'),
        ];
    }
}
