<?php

namespace App\Filament\Resources\TareasResource\Pages;

use App\Filament\Resources\TareasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use App\Filament\Resources\TareasResource\Widgets\TareasWidget;


class ListTareas extends ListRecords
{
    protected static string $resource = TareasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('deleteCompleted')
            ->label('Eliminar Completadas')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalHeading('¿Eliminar todas las tareas completadas?')
            ->modalDescription('Esta acción eliminará todas las tareas que estén marcadas como completadas. ¿Estás seguro?')
            ->action(function () {
                \App\Models\tareas::where('completada', true)->delete();
                Notification::make()
                    ->title('Tareas completadas eliminadas correctamente.')
                    ->success()
                    ->send();
            }),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            TareasWidget::class,
        ];
    }
}
