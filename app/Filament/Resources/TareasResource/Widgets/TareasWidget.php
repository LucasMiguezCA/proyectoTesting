<?php

namespace App\Filament\Resources\TareasResource\Widgets;

use App\Models\tareas;
use Filament\Forms\Components\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TareasWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $tareasCount = $this->getTareasCount();
        return [
            Stat::make('Total Tareas', $tareasCount['total'])
                ->description('Total de tareas registradas')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Tareas Completadas', $tareasCount['completadas'])
                ->description('Tareas que han sido completadas')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Tareas Pendientes', $tareasCount['pendientes'])
                ->description('Tareas que aún están pendientes')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
            Stat::make('Promedio Completadas', $tareasCount['promedio_completadas'] . '%')
            //Stat::make('Promedio Completadas', 100 . '%')
                ->description('Porcentaje de tareas completadas')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            ];
    }
    private function getTareasCount(): array
    {
        $total = tareas::count();
        $completadas = tareas::where('completada', true)->count();
        $pendientes = tareas::where('completada', false)->count();
        $promedioCompletadas = $total > 0 ? round(($completadas / $total) * 100, 2) : 0;
    
        return [
            'total' => $total,
            'completadas' => $completadas,
            'pendientes' => $pendientes,
            'promedio_completadas' => $promedioCompletadas, // Nuevo cálculo
        ];
    }
}
