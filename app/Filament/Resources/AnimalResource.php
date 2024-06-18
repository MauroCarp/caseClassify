<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimalResource\Pages;
use App\Filament\Resources\AnimalResource\RelationManagers;
use App\Models\Animal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;


class AnimalResource extends Resource
{
    protected static ?string $model = Animal::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationLabel = 'Animales'; // Cambiar el texto en el menú de navegación

    protected static ?string $pluralModelLabel = 'Animales';

    protected static ?string $modelLabel = 'Registro';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('category')
                    ->label('Categoria')
                    ->options([
                        'Vaquillona' => 'Vaquillona',
                        'Nsovillo' => 'Novillo',
                    ])
                    ->default('Vaquillona') // Establecer 'dorsal' como seleccionado por defecto
                    ->required(),
                Forms\Components\TextInput::make('rfid')
                    ->label('RFID')
                    ->required()
                    ->maxLength(191),
                    Forms\Components\TextInput::make('weight')
                    ->label('Peso')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('gd')
                    ->label('G.D')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('AoB')
                    ->label('AoB')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('gim')
                    ->label('% GIM')
                    ->required()
                    ->maxLength(191),
                FileUpload::make('images')
                    ->label('Ecografias')
                    ->multiple()
                    ->image()
                    ->directory('uploads/animals')
                    ->visibility('public')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                ->sortable()

                    ->searchable()
                    ->label('Categoria'),
                Tables\Columns\TextColumn::make('rfid')
                ->sortable()

                    ->searchable()
                    ->label('RFID'),
                Tables\Columns\TextColumn::make('weight')
                    ->sortable()
                    ->searchable()
                    ->label('Peso'),
                Tables\Columns\TextColumn::make('gd')
                    ->searchable()
                    ->label('G.D'),
                Tables\Columns\TextColumn::make('AoB')
                    ->sortable()
                    ->searchable()
                    ->label('AoB'),
                Tables\Columns\TextColumn::make('AoBType')
                    ->sortable()
                    ->searchable()
                    ->label('Tipo AoB'),
                Tables\Columns\TextColumn::make('case')
                    ->sortable()
                    ->searchable()
                    ->label('Carcasa'),
                Tables\Columns\TextColumn::make('im')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('I.M'),
                Tables\Columns\TextColumn::make('aobe')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('AOBE'),
                Tables\Columns\TextColumn::make('yg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('Yield Grade'),
                Tables\Columns\TextColumn::make('rc')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('% R.C'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('showEcos')
                ->label('Ecografias')
                ->icon('heroicon-o-photo')
                ->action(function (Animal $record) {
                    // Lógica de la acción personalizada
                    // Puedes hacer cualquier cosa aquí, por ejemplo, redirigir a una página diferente
                    return false;
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('assignToFolder')
                ->label('Asignar a Carpeta')
                ->icon('heroicon-o-arrow-up-on-square-stack')
                ->action(function (Animal $record) {
                    // Lógica de la acción personalizada
                    // Puedes hacer cualquier cosa aquí, por ejemplo, redirigir a una página diferente
                    return false;
                }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimals::route('/'),
            'create' => Pages\CreateAnimal::route('/create'),
            'edit' => Pages\EditAnimal::route('/{record}/edit'),
        ];
    }
}
