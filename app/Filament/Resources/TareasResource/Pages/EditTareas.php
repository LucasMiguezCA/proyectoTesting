<?php

namespace App\Filament\Resources\TareasResource\Pages;

use App\Filament\Resources\TareasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTareas extends EditRecord
{
    protected static string $resource = TareasResource::class;

    protected function afterSave(): void    
    {    
            $this->record->refresh(); // ✅ Asegura tener subtareas actualizadas

        // Si alguna subtarea NO está completada, la tarea tampoco debe estar completada
        if ($this->record->subtareas()->where('completada_subtarea', 0)->exists()) {
            $this->record->completada = 0;
            $this->record->save();
        }
        // Si la tarea fue marcada como completada
        if ($this->record->completada == 1) {
            $usuario = $this->record->user; // Ajusta el nombre de la relación si es diferente
            if ($usuario) {                
                $usuario->increment('puntos', 5);
            }
        }else{
            $usuario = $this->record->user; // Ajusta el nombre de la relación si es diferente
            if ($usuario) {                
                $usuario->decrement('puntos', 5);
            }
        }
    }

    

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
