<?php

namespace App\Filament\Resources\SimpananResource\Pages;

use App\Filament\Resources\SimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSimpanan extends EditRecord
{
    protected static string $resource = SimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
