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
        if ($this->record->completada == 1) {
            $usuario = $this->record->user; // Ajusta el nombre de la relación si es diferente
            if ($usuario) {
                $usuario->increment('puntos', 5);
            }
        }
    }
}
