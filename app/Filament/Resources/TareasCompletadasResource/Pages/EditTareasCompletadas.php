<?php

namespace App\Filament\Resources\TareasCompletadasResource\Pages;

use App\Filament\Resources\TareasCompletadasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTareasCompletadas extends EditRecord
{
    protected static string $resource = TareasCompletadasResource::class;
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si completada es 0, actualiza estado a 1
        if (isset($data['completada']) && !$data['completada']) {
            $data['estado'] = 1;
        }
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
