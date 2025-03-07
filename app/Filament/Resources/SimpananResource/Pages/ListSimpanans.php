<?php

namespace App\Filament\Resources\SimpananResource\Pages;

use App\Filament\Resources\SimpananResource;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SimpananResource\Widgets\SimpananOverview;

class ListSimpanans extends ListRecords
{
    public ?Model $record = null;

    protected static string $resource = SimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SimpananOverview::class,
        ];
    }

}
