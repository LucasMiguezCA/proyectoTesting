<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TareasResource\Pages;
use App\Filament\Resources\TareasResource\RelationManagers;
use App\Models\tareas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class TareasResource extends Resource
{
    protected static ?string $model = tareas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descripcion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('completada'),
                Forms\Components\Checkbox::make('importante'),
                Forms\Components\Select::make('categoria')
                    ->label('Categoría')
                    ->options([
                        'Personal' => 'Personal',
                        'Estudio' => 'Estudio',
                        'Trabajo' => 'Trabajo',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('fecha_vencimiento')
                    ->label('Fecha de Vencimiento')
                    ->required(),
                Forms\Components\Repeater::make('subtareas')
                ->relationship('subtareas')
                ->schema([
                    Forms\Components\TextInput::make('Contenido')
                        ->label('Nombre de la Subtarea')
                        ->required(),
                    Forms\Components\Checkbox::make('Completada')
                        ->label('Completada'),
                ])
                ->addActionLabel('Agregar Subtarea')
                ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoría')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Personal' => 'danger',
                        'Estudio' => 'success',
                        'Trabajo' => 'warning',
                        default => 'secondary',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('completada')
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ? 'Completada' : 'No completada'),

                Tables\Columns\IconColumn::make('importante')
                    ->label('Importante')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')      // Estrella llena
                    ->falseIcon('heroicon-o-star')
                    ->toggleable(),
                // Tables\Columns\TextColumn::make('fecha_vencimiento')
                //     ->label('Fecha de Vencimiento')
                //     ->date()
                //     ->color(fn($record) => $record->fecha_vencimiento < now()->toDateString() ? 'danger' : null)
                //     ->icon(fn($record) => $record->fecha_vencimiento < now()->toDateString() ? 'heroicon-o-exclamation-triangle' : null)
                //     ->iconColor('danger'),
                //Codigo correcto
                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Fecha de Vencimiento')
                    ->date()
                    ->color(
                        fn($record) =>
                        $record->fecha_vencimiento < now()->toDateString() && !$record->completada
                            ? 'danger'
                            : null
                    )
                    ->icon(
                        fn($record) =>
                        $record->fecha_vencimiento < now()->toDateString() && !$record->completada
                            ? 'heroicon-o-exclamation-triangle'
                            : null
                    )
                    ->iconColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTareas::route('/'),
            'create' => Pages\CreateTareas::route('/create'),
            'view' => Pages\ViewTareas::route('/{record}'),
            'edit' => Pages\EditTareas::route('/{record}/edit'),
        ];
    }
}
