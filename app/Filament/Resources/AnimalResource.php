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

        $engMeasures = array('libras'=>2.204,'inch'=>0.0393,'inch2'=>0.155,'kph%'=>3.3);

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
                Tables\Columns\TextColumn::make('case')
                    ->sortable()
                    ->searchable()
                    ->label('Carcasa')
                    ->getStateUsing(function ($record) {

                        $case = $record->weight * 0.59; 
                        return number_format($case, 0);

                    }),
                Tables\Columns\TextColumn::make('im')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('I.M')
                    ->getStateUsing(function ($record) {

                        $indiceMuscularidad = ($record->AoB / $record->weight) * 100; 
                        return number_format($indiceMuscularidad, 0);

                    }),
                Tables\Columns\TextColumn::make('yg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('Yield Grade')
                    ->getStateUsing(function ($record) use ($engMeasures) {

                        $case = $record->weight * 0.59; 

                        $n1 = $record->gd * $engMeasures['inch'];
                        $n2 = $engMeasures['kph%'];
                        $n3 = $case * $engMeasures['libras'];
                        $n4 = $record->AoB * $engMeasures['inch2'];
            
                        $yieldGrade = 2.5+(2.5*$n1)+(0.2*$n2)+(0.0038*$n3)-(0.32*$n4); 

                        return number_format($yieldGrade, 1);

                    }),
                Tables\Columns\TextColumn::make('rc')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable()
                    ->label('% R.C')
                    ->getStateUsing(function ($record) use ($engMeasures) {

                        $case = $record->weight * 0.59; 

                        $n1 = $record->gd * $engMeasures['inch'];
                        $n2 = $engMeasures['kph%'];
                        $n3 = $case * $engMeasures['libras'];
                        $n4 = $record->AoB * $engMeasures['inch2'];

                        $rc = 65.59-(9.93*$n1)-(1.29*$n2)+(1.23*$n4)-(0.013*$n3); 

                        return number_format($rc, 2);

                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('showMore')
                ->label('Ver Más')
                ->icon('heroicon-o-eye')
                ->action(function (Animal $record) {
                    // Lógica de la acción personalizada
                    // Puedes hacer cualquier cosa aquí, por ejemplo, redirigir a una página diferente
                    return false;
                }),
                Tables\Actions\EditAction::make(),

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
