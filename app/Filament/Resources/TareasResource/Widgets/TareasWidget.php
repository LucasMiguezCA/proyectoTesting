<?php

namespace App\Filament\Resources\TareasResource\Widgets;

use App\Models\tareas;
use App\Models\Subtarea;
use Filament\Forms\Components\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TareasWidget extends BaseWidget
{
    protected function getStats(): array
    {
         $user = Auth::user();
        $medallas = $user ? floor($user->puntos / 25) : 0;
        $tareasCount = $this->getTareasCount();
        return [
            Stat::make('Total Tareas Actuales', $tareasCount['total'])
                ->description('Total de tareas actuales')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Tareas Completadas', $tareasCount['completadas'])
                ->description('Tareas que han sido completadas')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Tareas Pendientes', $tareasCount['pendientes'])
                ->description('Tareas que aún están pendientes')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
            Stat::make(
                'Promedio Completadas',
                number_format($tareasCount['promedio_completadas'], 2) . '%'
            )
                ->description('Porcentaje de tareas completadas')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
                Stat::make('Medallas', $medallas)
            ->description('Medallas obtenidas (cada 25 puntos)')
            ->icon('heroicon-o-trophy')
            ->color('warning'),
        ];
    }

    private function getTareasCount(): array
    {
        // Tareas
    $totalTareasActuales = tareas::where('estado', 1)->count();
    $totalTareas = tareas::count();
    $tareasCompletadas = tareas::where('completada', true)->count();
    $tareasPendientes = tareas::where('completada', false)->count();

    // Subtareas
    $totalSubtareas = \App\Models\Subtarea::whereHas('tareas', function($q) {
        $q->where('estado', 1);
    })->count();

    $subtareasCompletadas = \App\Models\Subtarea::whereHas('tareas', function($q) {
        $q->where('estado', 1);
    })->where('completada_subtarea', true)->count();

    $subtareasPendientes = \App\Models\Subtarea::whereHas('tareas', function($q) {
        $q->where('estado', 1);
    })->where('completada_subtarea', false)->count();

    // Totales
    $total = $totalTareas ;
    $completadas = $tareasCompletadas;
    $pendientes = $tareasPendientes;

    $promedioCompletadas = ($total) > 0 ? round((($completadas) / ($total)) * 100, 2) : 0;

    return [
        'total' => $totalTareasActuales,
        'completadas' => $completadas,
        'pendientes' => $pendientes,
        'promedio_completadas' => $promedioCompletadas,
    ];
    }
}
