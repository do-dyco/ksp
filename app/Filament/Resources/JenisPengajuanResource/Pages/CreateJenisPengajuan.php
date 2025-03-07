<?php

namespace App\Filament\Resources\JenisPengajuanResource\Pages;

use App\Filament\Resources\JenisPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisPengajuan extends CreateRecord
{
    protected static string $resource = JenisPengajuanResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
