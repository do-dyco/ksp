<?php

namespace App\Filament\Resources\PinjamanResource\Pages;

use App\Filament\Resources\PinjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePinjaman extends CreateRecord
{
    protected static string $resource = PinjamanResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
