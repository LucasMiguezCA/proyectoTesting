<?php

namespace App\Filament\Resources\TareasResource\Pages;

use App\Filament\Resources\TareasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTareas extends CreateRecord
{
    protected static string $resource = TareasResource::class;
    protected function afterCreate(): void
    {
        // Si la tarea fue creada como completada
        if ($this->record->completada) {
            $usuario = $this->record->users; // Cambia 'users' por el nombre correcto de la relación si es necesario
            if ($usuario) {
                $usuario->increment('puntos', 5);
            }
        }
    }
}
