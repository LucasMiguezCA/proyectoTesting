<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TareasCompletadasResource\Pages;
use App\Filament\Resources\TareasCompletadasResource\RelationManagers;
use App\Models\tareas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TareasCompletadasResource extends Resource
{
    protected static ?string $model = tareas::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Tareas Completadas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('completada'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Tareas::where('completada', 1);
            })
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->searchable(),
                Tables\Columns\TextColumn::make('categoria')->label('Categoría'),
                Tables\Columns\TextColumn::make('completada')
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ? 'Completada' : 'No completada'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTareasCompletadas::route('/'),
            'create' => Pages\CreateTareasCompletadas::route('/create'),
            'edit' => Pages\EditTareasCompletadas::route('/{record}/edit'),
        ];
    }
}
