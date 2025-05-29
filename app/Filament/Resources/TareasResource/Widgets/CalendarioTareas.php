<?php

namespace App\Filament\Resources\TareasResource\Widgets;

use App\Models\tareas;
use Filament\Widgets\Widget;

class CalendarioTareas extends Widget
{

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    protected static string $view = 'filament.resources.tareas-resource.widgets.calendario-tareas';
    public function getViewData(): array
    {
        $diasSemana = [
            'Monday'    => 'Lunes',
            'Tuesday'   => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday'  => 'Jueves',
            'Friday'    => 'Viernes',
            'Saturday'  => 'Sábado',
            'Sunday'    => 'Domingo',
        ];

        $inicioSemana = now()->startOfWeek(); // Lunes
        $finSemana = now()->endOfWeek();      // Domingo

        $tareasPorDia = tareas::whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [$inicioSemana, $finSemana])
            ->get()
            ->groupBy(fn($tarea) => \Carbon\Carbon::parse($tarea->fecha_vencimiento)->format('l'));

        $tareasPorDiaOrdenado = [];
        foreach ($diasSemana as $en => $es) {
            $tareasPorDiaOrdenado[$es] = $tareasPorDia[$en] ?? collect();
        }

        // Día de hoy en español
        $hoy = $diasSemana[now()->format('l')];
        return compact('tareasPorDiaOrdenado', 'hoy');
    }
}
