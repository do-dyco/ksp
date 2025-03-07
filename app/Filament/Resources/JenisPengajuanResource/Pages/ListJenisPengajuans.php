<?php

namespace App\Filament\Resources\JenisPengajuanResource\Pages;

use App\Filament\Resources\JenisPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisPengajuans extends ListRecords
{
    protected static string $resource = JenisPengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
