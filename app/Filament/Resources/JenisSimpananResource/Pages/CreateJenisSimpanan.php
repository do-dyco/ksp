<?php

namespace App\Filament\Resources\JenisSimpananResource\Pages;

use App\Filament\Resources\JenisSimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisSimpanan extends CreateRecord
{
    protected static string $resource = JenisSimpananResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
