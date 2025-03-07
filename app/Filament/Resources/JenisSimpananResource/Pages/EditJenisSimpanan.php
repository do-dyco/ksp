<?php

namespace App\Filament\Resources\JenisSimpananResource\Pages;

use App\Filament\Resources\JenisSimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisSimpanan extends EditRecord
{
    protected static string $resource = JenisSimpananResource::class;

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
