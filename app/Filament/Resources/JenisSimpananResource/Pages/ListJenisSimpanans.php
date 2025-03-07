<?php

namespace App\Filament\Resources\JenisSimpananResource\Pages;

use App\Filament\Resources\JenisSimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisSimpanans extends ListRecords
{
    protected static string $resource = JenisSimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
