<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimalResource\Widgets\AnimalFolderTable;
use App\Filament\Resources\FolderResource\Pages;
use App\Models\Folder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FolderResource extends Resource
{
    protected static ?string $model = Folder::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Carpetas'; // Cambiar el texto en el menú de navegación

    protected static ?string $pluralModelLabel = 'Carpetas';

    protected static ?string $modelLabel = 'Carpeta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label('Carpeta'),
                Forms\Components\Select::make('userId')
                ->options(User::where('isAdmin',0)->pluck('name', 'id')->toArray())
                ->label('Usuario')
                ->searchable()
                ->preload()
                ->required(),
                Forms\Components\DatePicker::make('date')
                ->required()
                ->label('Fecha Entrega')
                ->minDate(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Carpeta'),
                Tables\Columns\TextColumn::make('user.name')->label('Usuario'),
                Tables\Columns\TextColumn::make('date')
                ->getStateUsing(fn ($record) => Carbon::parse($record->date)->format('d-m-Y'))
                ->label('Fecha'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->color('primary'),
                Action::make('makeInform')
                ->label('Informe')
                ->icon('heroicon-o-information-circle')
                ->requiresConfirmation()
                ->url(
                    fn ($record): string => route('pdf.inform',['folder'=>$record]),
                    shouldOpenInNewTab: true
                ),
                
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

    public static function getWidgets(): array
    {
        return [
            AnimalFolderTable::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFolders::route('/'),
            'create' => Pages\CreateFolder::route('/create'),
            'edit' => Pages\EditFolder::route('/{record}/edit'),
            'view' => Pages\ViewFolder::route('/{record}'),        
        ];
    }
}
