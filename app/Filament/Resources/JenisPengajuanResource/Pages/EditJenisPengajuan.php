<?php

namespace App\Filament\Resources\JenisPengajuanResource\Pages;

use App\Filament\Resources\JenisPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisPengajuan extends EditRecord
{
    protected static string $resource = JenisPengajuanResource::class;

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
