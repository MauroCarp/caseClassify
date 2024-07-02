<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimalResource\Pages;
use App\Models\Animal;
use App\Models\Folder;
use App\Models\Raze;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid as FormGrid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split as ComponentsSplit;
use Illuminate\Database\Eloquent\Model;

class AnimalResource extends Resource
{
    protected static ?string $model = Animal::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationLabel = 'Animales'; // Cambiar el texto en el menú de navegación

    protected static ?string $pluralModelLabel = 'Animales';

    protected static ?string $modelLabel = 'Animal';

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
                    Forms\Components\TextInput::make('rfid')
                        ->label('RFID')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\Select::make('raze')
                        ->options(Raze::pluck('name', 'id')->toArray())
                        ->label('Raza')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Raza')
                                ->required(),
                        ])
                        ->createOptionUsing(function (array $data): int {
                            $raze = Raze::create(['name' => $data['name']]);
                            return $raze->id;
                        }),

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
    
                    ComponentsSplit::make([
                        FormGrid::make(2)
                        ->schema([
                            Forms\Components\Radio::make('category')
                                ->label('Categoria')
                                ->options([
                                    'Vaquillona' => 'Vaquillona',
                                    'Novillo' => 'Novillo',
                                ])
                                ->default('Vaquillona')
                                ->required(),
                                ComponentsSplit::make([
                                FormGrid::make(1)
                                ->schema([
                                    Forms\Components\Toggle::make('isAngus')
                                        ->label('Argentine Angus Beef'),
                                    Forms\Components\Toggle::make('isHilton')
                                        ->label('Cuota Hilton'),
                                ])
                            ]),
                            ])
                        ]),
                        FileUpload::make('images')
                            ->label('Ecografias')
                            ->multiple()
                            ->image()
                            ->directory('uploads/animals')
                            ->visibility('public'),                      


                // FormGrid::make(4)
                //     ->schema([
                //         FormGrid::make(3)
                //             ->schema([
                //                 Forms\Components\Radio::make('category')
                //                     ->label('Categoria')
                //                     ->options([
                //                         'Vaquillona' => 'Vaquillona',
                //                         'Novillo' => 'Novillo',
                //                     ])
                //                     ->default('Vaquillona')
                //                     ->required(),
                //                 Forms\Components\Toggle::make('isAngus')
                //                     ->label('Aberdeen Angus'),
                //                 Forms\Components\Toggle::make('isHilton')
                //                     ->label('Cuota Hilton'),

                //             ]),
                //         FormGrid::make(1)
                //             ->schema([
                //                 FileUpload::make('images')
                //                     ->label('Ecografias')
                //                     ->multiple()
                //                     ->image()
                //                     ->directory('uploads/animals')
                //                     ->visibility('public')
                //             ]),

                //     ])
        ]);
    }

    public static function table(Table $table): Table
    {

        $engMeasures = array('libras'=>2.204,'inch'=>0.0393,'inch2'=>0.155,'kph%'=>3.3);

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->sortable()
                    ->label('Categoria'),
                Tables\Columns\TextColumn::make('rfid')
                    ->label('RFID'),
                Tables\Columns\TextColumn::make('weight')
                    ->sortable()
                    ->label('Peso'),
                Tables\Columns\TextColumn::make('gd')
                    ->label('G.D'),
                Tables\Columns\TextColumn::make('AoB')
                    ->sortable()
                    ->label('AoB'),
                Tables\Columns\TextColumn::make('case')
                    ->sortable()
                    ->label('Carcasa')
                    ->getStateUsing(function ($record) {

                        $case = $record->weight * 0.59; 
                        return number_format($case, 0);

                    }),
                Tables\Columns\TextColumn::make('gim')
                    ->sortable()
                    ->label('% G.I.M'),
                Tables\Columns\TextColumn::make('assigned')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Asignado')
                    ->badge()
                    ->colors([
                        'success' => fn ($state): bool => $state,
                        'danger' => fn ($state): bool => !$state,
                    ])
                    ->icons([
                        'heroicon-o-check' => fn ($state): bool => $state,
                        'heroicon-o-x-circle' => fn ($state): bool => !$state,
                    ])
                    ->getStateUsing(fn ($record) => !is_null($record->idFolder)),

                Tables\Columns\TextColumn::make('im')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label('I.M')
                    ->getStateUsing(function ($record) {

                        $indiceMuscularidad = ($record->AoB / $record->weight) * 100; 
                        return number_format($indiceMuscularidad, 0);

                    }),
                Tables\Columns\TextColumn::make('yg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
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
                Tables\Columns\TextColumn::make('grade')
                    ->label('Grado')
                    ->getStateUsing(function ($record){

                        $grado = self::getGrade($record->gim,'grade');
                        $name = self::getGrade($record->gim,'name');
                        return $grado . ' - ' . $name;

                }),
                ImageColumn::make('gradeImage')
                    ->getStateUsing(function ($record) {

                        $gim = $record->gim;
                        $name = self::getGrade($gim,'name');
                        $name = strtolower(str_replace(' ','',$name));
                        $gradeImage = "marbling\/$name.png";

                        return $gradeImage;
                    })
                    ->label(''),
                ImageColumn::make('gradeMedal')
                    ->getStateUsing(function ($record) {

                        $gim = $record->gim;
                        $medal = self::getGrade($gim,'medal');
                        $medalImage = "images\/$medal.png";

                        return $medalImage;
                    })
                    ->label('')
                    ->tooltip(function (Model $record){
                        $gim = $record->gim;
                        $medal = self::getGrade($gim,'medal');
                        return ucfirst($medal);
                    }),
                ImageColumn::make('isAngus')
                    ->getStateUsing(function ($record) {

                        $angusImage = '';

                        if($record->isAngus)
                            $angusImage = "images\angusLogo.png";

                        return $angusImage;

                    })
                    ->label('')
                    ->tooltip('Argentine Angus Beef'),
                ImageColumn::make('isHilton')
                    ->getStateUsing(function ($record) {

                        $hiltonImage = '';

                        if($record->isHilton)
                            $hiltonImage = "images\/cuotaHilton.png";

                        return $hiltonImage;
                    })
                    ->label('')
                    ->tooltip('Cuota Hilton'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Categoria')
                    ->options([
                        'Vaquillona' => 'Vaquillona',
                        'Novillo' => 'Novillo',
                    ])
                    ->preload(),
                SelectFilter::make('grade')
                    ->label('Grado')
                    ->options([
                        '6 - Abundante' => '6 - Abundante',
                        '5 - Moderado' => '5 - Moderado',
                        '4 - Modesto' => '4 - Modesto',
                        '3 - Escaso' => '3 - Escaso',
                        '2 - Muy Escaso' => '2 - Muy Escaso',
                        '1 - Trazas' => '1 - Trazas',
                        '0 - Sin Grasa' => '0 - Sin Grasa',
                    ])
                    ->query(function ($query, array $data) {
                        
                        switch ($data['value']) {
                            case '6 - Abundante':
                                $calc = "gim > 6.5";
                                break;
                            case '5 - Modesto':
                                $calc = "gim > 5 ANDgim<= 6.5";
                                break;
                            case '4 - Moderado':
                                $calc = "gim > 4 AND gim <= 5";
                                break;
                            case '3 - Escaso':
                                $calc = "gim> 3 AND gim <= 4";
                                break;
                            case '2 - Muy Escaso':
                                $calc = "gim 1.8 AND gim <= 3";
                                break;
                            case '1 - Trazas':
                                $calc = "gim > 0.5 AND gim <= 1.8";
                                break;
                            case '0 - Sin Grasa':
                                $calc = "gim <= 0.5";
                                break;
                            
                            default:
                                $calc = "gim BETWEEN 0 AND 7";
                            break;
                        }

                        $a =  DB::select('select gim from animals where '. $calc);
                        return $a;

                    })  
                    ->preload(),
               
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->color('primary')
                ->modalHeading('Detalles del Animal'),
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('assignToFolder')
                        ->label('Asignar a Carpeta')
                        ->icon('heroicon-o-arrow-up-on-square-stack')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records, array $data) {
                            // Lógica para asignar los registros a una carpeta
                            foreach ($records as $record) {
                                $record->idFolder = $data['idFolder']; // Asigna el ID de la carpeta
                                $record->save();
                            }
        
                            // Mensaje de éxito
                        })
                        // ->success('Registros asignados a la carpeta exitosamente.')
                        ->form([
                            Select::make('idFolder')
                                ->label('Carpeta')
                                ->options(Folder::all()->pluck('name', 'id')->toArray()) // Obtén las opciones de las carpetas
                                ->required(),
                        ]),
                ])
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $engMeasures = array('libras'=>2.204,'inch'=>0.0393,'inch2'=>0.155,'kph%'=>3.3);

        return $infolist
        ->schema([
            InfolistSection::make()
                ->schema([
                    Split::make([
                        Grid::make(4)
                            ->schema([
                                Group::make([
                                    TextEntry::make('category')
                                        ->label('Categoria')
                                        ->size('lg')
                                        ->weight('bold'),                            
                                    TextEntry::make('gim')
                                        ->label('Grasa Intramuscular')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->getStateUsing(function ($record) {
                
                                            return $record->gim . ' %';
                                            
                                        }),                           
                                        TextEntry::make('AoB')
                                        ->label('AoB')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->getStateUsing(function ($record) {
                
                                            return $record->AoB . ' cm²';
                                            
                                        }),       
                                        TextEntry::make('im')
                                        ->label('I.M')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->getStateUsing(function ($record) {
                
                                            $indiceMuscularidad = ($record->AoB / $record->weight) * 100; 
                                            return number_format($indiceMuscularidad, 0);
                                            
                                        }),       
                                    ]),
                                Group::make([
                                    TextEntry::make('rfid')->size('lg')
                                        ->size('lg')
                                        ->label('RFID')
                                        ->weight('bold'),
                                    TextEntry::make('gd')
                                        ->label('Grasa Dorsal')
                                        ->size('lg')
                                        ->weight('bold'),
                                    TextEntry::make('case')
                                        ->label('Carcasa')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->getStateUsing(function ($record) {
                
                                            $case = $record->weight * 0.59; 
                                            return number_format($case, 0) . ' Kg';

                                        }),
                                    TextEntry::make('yg')
                                        ->label('Yield Grade')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->getStateUsing(function ($record) use ($engMeasures) {
                
                                            $case = $record->weight * 0.59; 

                                            $n1 = $record->gd * $engMeasures['inch'];
                                            $n2 = $engMeasures['kph%'];
                                            $n3 = $case * $engMeasures['libras'];
                                            $n4 = $record->AoB * $engMeasures['inch2'];
                                
                                            $yieldGrade = 2.5+(2.5*$n1)+(0.2*$n2)+(0.0038*$n3)-(0.32*$n4); 
                    
                                            return number_format($yieldGrade, 1);

                                        }),
                                ]),
                                Group::make([
                                    TextEntry::make('weight')
                                        ->label('Peso')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->getStateUsing(function ($record) {
                
                                            return $record->weight . ' Kg';
                                            
                                        }), 
                                    TextEntry::make('AoB')
                                        ->label('Area Ojo de Bife')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->getStateUsing(function ($record) {
                
                                            return $record->AoB . ' cm²';
                                            
                                        }),  
                                    TextEntry::make('rc')
                                        ->getStateUsing(function ($record) use ($engMeasures) {

                                            $case = $record->weight * 0.59; 
                    
                                            $n1 = $record->gd * $engMeasures['inch'];
                                            $n2 = $engMeasures['kph%'];
                                            $n3 = $case * $engMeasures['libras'];
                                            $n4 = $record->AoB * $engMeasures['inch2'];
                    
                                            $rc = 65.59-(9.93*$n1)-(1.29*$n2)+(1.23*$n4)-(0.013*$n3); 
                    
                                            return number_format($rc, 2);
                                        })
                                        ->label('% R.C')
                                        ->size('lg')
                                        ->weight('bold'),
                                    TextEntry::make('ms')
                                        ->getStateUsing(function ($record) use ($engMeasures) {

                                            $gim = $record->gim;

                                            $ms = ((769.7 + (56.69 * $gim)) / 100) - 5;
                                            return number_format($ms, 1);
                                        })
                                        ->label('Marbling Score')
                                        ->size('lg')
                                        ->weight('bold'), 
                                    ]),
                                Group::make([
                                    TextEntry::make('grade')
                                        ->getStateUsing(function ($record) {
                
                                            $gim = $record->gim;
                                            $grade = self::getGrade($gim,'grade');
                                            $name = self::getGrade($gim,'name');
                
                                            return $grade . ' - ' . $name;
                                        })
                                        ->label('Grado')
                                        ->size('lg')
                                        ->weight('bold'),
                                    ImageEntry::make('grade-image')
                                        ->hiddenLabel()  
                                        ->visibility('private')
                                        ->height(150)
                                        ->grow(false)
                                        ->extraImgAttributes([
                                            'class' => 'mx-auto block',
                                            'loading' => 'lazy',
                                        ])
                                        ->getStateUsing(function ($record) {
                
                                            $gim = $record->gim;
                                            $name = self::getGrade($gim,'name');
                                            $name = strtolower(str_replace(' ','',$name));
                                            $gradeImage = "marbling\/$name.png";
                
                                            return $gradeImage;
                                        }),
                                    Split::make([
                                        Grid::make(3)
                                            ->schema([
                                                ImageEntry::make('gradeMedal')
                                                    ->hiddenLabel()  
                                                    ->visibility('private')
                                                    ->height(60)
                                                    ->grow(false)
                                                    ->extraImgAttributes([
                                                        'class' => 'mx-auto block',
                                                        'loading' => 'lazy',
                                                    ])
                                                    ->getStateUsing(function ($record) {
                            
                                                        $gim = $record->gim;
                                                        $medal = self::getGrade($gim,'medal');
                                                        $gradeMedal = "images\/$medal.png";
                            
                                                        return $gradeMedal;
                                                    })
                                                    ->tooltip(function (Model $record){
                                                        $gim = $record->gim;
                                                        $medal = self::getGrade($gim,'medal');
                                                        return ucfirst($medal);
                                                    }),
                                                ImageEntry::make('isAngus')
                                                    ->hiddenLabel()  
                                                    ->visibility('private')
                                                    ->height(85)
                                                    ->grow(false)
                                                    ->extraImgAttributes([
                                                        'class' => 'mx-auto block',
                                                        'loading' => 'lazy',
                                                    ])
                                                    ->getStateUsing(function ($record) {
                            
                                                        $isAngus = '';

                                                        if($record->isAngus){
                                                            $isAngus = "images\aberdeenAngus.png";
                                                        }

                                                        return $isAngus;
                            
                                                    })
                                                    ->tooltip('Argentine Angus Beef'),
                                                ImageEntry::make('isHilton')
                                                    ->hiddenLabel()  
                                                    ->visibility('private')
                                                    ->height(35)
                                                    ->grow(false)
                                                    ->extraImgAttributes([
                                                        'class' => 'mx-auto block',
                                                        'loading' => 'lazy',
                                                    ])
                                                    ->getStateUsing(function ($record) {
                            
                                                        $isHilton = '';

                                                        if($record->isHilton){
                                                            $isHilton = "images\cuotaHilton.png";
                                                        }

                                                        return $isHilton;
                            
                                                    })
                                                    ->tooltip('Cuota Hilton'),
                                                    

                                            ])
                                    ])
                                    
                                ]),
                            ]),
                    ])->from('lg'),
                ]),
            InfolistSection::make('Ecografias')
                ->schema([
                    TextEntry::make('images')
                    ->hiddenLabel()
                    ->getStateUsing(function ($record) {

                        $media = $record->images; // Obtén la URL de la primera imagen

                        return view('components.image-view', ['urls' => $media]);
                    }),
                ])
                ->collapsible(),
        ]);
        }

    public static function getRelations(): array
    {
        return [
            //  
        ];
    }

    public static function getGrade($gim, $type): string
    {
        
        switch ($gim) {
            case $gim > 6.5:
                $grade = 6;
                $name = 'Abundante';
                $quality = 'Prime';
                $medal = 'platinium';
                break;
            case ($gim > 5 && $gim <= 6.5):
                $grade = 5;
                $name = 'Moderado';
                $quality = 'Prime';
                $medal = 'platinium';

                break;
            
            case ($gim > 4 && $gim <= 5):
                $grade = 4;
                $name = 'Modesto';
                $quality = 'Choice';
                $medal = 'gold';

                break;
            
            case ($gim > 3 && $gim <= 4):
                $grade = 3;
                $name = 'Escaso';
                $quality = 'Choice';
                $medal = 'gold';

                break;
            
            case ($gim > 1.8 && $gim <= 3):
                $grade = 2;
                $name = 'Muy Escaso';
                $quality = 'Choice';
                $medal = 'gold';

                break;

            case ($gim > 0.5 && $gim <= 1.8):
                $grade = 1;
                $name = 'Trazas';
                $quality = 'Select';
                $medal = 'silver';

                break;

            case ($gim <= 0.5):
                $grade = 0;
                $name = 'Sin Grasa';
                $quality = 'Select';
                $medal = 'silver';

                break;
            
            default:
                # code...
                break;
        }

        if($type == 'grade'){
            return $grade;
        } else if($type == 'quality'){
            return $quality;
        } else if($type == 'medal'){
            return $medal;
        } else {
            return $name;
        }

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
?>
