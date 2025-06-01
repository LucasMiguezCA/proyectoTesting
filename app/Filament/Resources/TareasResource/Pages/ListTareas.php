<?php

namespace App\Filament\Resources\TareasResource\Pages;

use App\Filament\Resources\TareasResource;
use App\Filament\Resources\TareasResource\Widgets\CalendarioTareas;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\TareasResource\Widgets\TareasWidget;
use App\Models\tareas;
use Carbon\Carbon;;


class ListTareas extends ListRecords
{
    protected static string $resource = TareasResource::class;

    public function mount(...$params): void
    {
        parent::mount(...$params);

        // $user = Auth::user();
        // if (
        //     $user &&
        //     $user->puntos > 0 &&
        //     $user->puntos % 25 === 0 &&
        //     !session()->has('felicitacion_mostrada_' . $user->id . '_' . $user->puntos)
        // ) {
        //     Notification::make()
        //         ->title('¡Felicidades!')
        //         ->body('Has completado ' . ($user->puntos / 5) . ' tareas y sumado ' . $user->puntos . ' puntos.')
        //         ->success()
        //         ->send();

        //     // Marca la notificación como mostrada para este usuario y cantidad de puntos
        //     session()->put('felicitacion_mostrada_' . $user->id . '_' . $user->puntos, true);
        // }

        $tareas = tareas::whereDate('fecha_vencimiento', Carbon::tomorrow())
            ->where('completada', false)
            ->get();

        foreach ($tareas as $tarea) {
            Notification::make()
                ->title('Tarea próxima a vencer')
                ->body("La tarea '{$tarea->nombre}' vence mañana.")
                ->warning()
                ->persistent()
                ->send();
        }
    }

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
                    \App\Models\tareas::where('completada', 1)->update(['estado' => 0]);
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
            CalendarioTareas::class,
        ];
    }
}
