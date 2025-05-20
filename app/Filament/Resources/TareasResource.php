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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('completada')
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ? 'Completada' : 'No completada'),
                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoría')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Personal' => 'primary',
                        'Estudio' => 'success',
                        'Trabajo' => 'warning',
                        default => 'secondary',
                    })
                    ->searchable(),
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
