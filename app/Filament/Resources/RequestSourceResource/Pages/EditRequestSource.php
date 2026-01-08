<?php

namespace App\Filament\Resources\RequestSourceResource\Pages;

use App\Filament\Resources\RequestSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestSource extends EditRecord
{
    protected static string $resource = RequestSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
