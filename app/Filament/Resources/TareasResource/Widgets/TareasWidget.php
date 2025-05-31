<?php

namespace App\Filament\Resources\TareasResource\Widgets;

use App\Models\tareas;
use App\Models\Subtarea;
use Filament\Forms\Components\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TareasWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $tareasCount = $this->getTareasCount();
        return [
            Stat::make('Total Tareas y Subtareas', $tareasCount['total'])
                ->description('Total de tareas registradas')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Tareas y Subtareas Completadas', $tareasCount['completadas'])
                ->description('Tareas que han sido completadas')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Tareas y Subtareas Pendientes', $tareasCount['pendientes'])
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
        ];
    }

    private function getTareasCount(): array
    {
        // Tareas
    $totalTareas = tareas::where('estado', 1)->count();
    $tareasCompletadas = tareas::where('estado', 1)->where('completada', true)->count();
    $tareasPendientes = tareas::where('estado', 1)->where('completada', false)->count();

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
    $total = $totalTareas + $totalSubtareas;
    $completadas = $tareasCompletadas + $subtareasCompletadas;
    $pendientes = $tareasPendientes + $subtareasPendientes;

    $promedioCompletadas = $total > 0 ? round(($completadas / $total) * 100, 2) : 0;

    return [
        'total' => $total,
        'completadas' => $completadas,
        'pendientes' => $pendientes,
        'promedio_completadas' => $promedioCompletadas,
    ];
    }
}
