<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreateScreening extends CreateRecord
{
    protected static string $resource = ScreeningResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['start_time'] = Carbon::createFromFormat('d/m/Y H:i', $data['start_time'])->format('Y-m-d H:i:s');
        return $data;
    }
}
