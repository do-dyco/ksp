<?php

namespace App\Filament\Resources\PinjamanResource\Pages;

use App\Filament\Resources\PinjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPinjaman extends EditRecord
{
    protected static string $resource = PinjamanResource::class;

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
