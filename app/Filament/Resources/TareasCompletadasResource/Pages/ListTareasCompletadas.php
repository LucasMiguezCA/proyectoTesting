<?php

namespace App\Filament\Resources\TareasCompletadasResource\Pages;

use App\Filament\Resources\TareasCompletadasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTareasCompletadas extends ListRecords
{
    protected static string $resource = TareasCompletadasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
