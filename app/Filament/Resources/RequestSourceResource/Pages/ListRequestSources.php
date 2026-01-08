<?php

namespace App\Filament\Resources\RequestSourceResource\Pages;

use App\Filament\Resources\RequestSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestSources extends ListRecords
{
    protected static string $resource = RequestSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
