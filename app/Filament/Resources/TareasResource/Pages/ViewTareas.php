<?php

namespace App\Filament\Resources\TareasResource\Pages;

use App\Filament\Resources\TareasResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Subtarea;
use Illuminate\Support\Facades\Log;

class ViewTareas extends ViewRecord
{
    protected static string $resource = TareasResource::class;

    public function mount($record): void
    {
        parent::mount($record);                
        Log::info($this->record->subtareas()->toSql());
        Log::info($this->record->subtareas()->getBindings());
        $subtareas = $this->record->subtareas()->get();
        Log::info('Resultados de subtareas:', $subtareas->toArray());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
