<?php

namespace App\Filament\Resources\SimpananResource\Pages;

use Filament\Actions;
use App\Models\Simpanan;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SimpananResource;

class CreateSimpanan extends CreateRecord
{
    protected static string $resource = SimpananResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $savings = [
            ['type' => 1, 'jumlah_simpanan' => $data['pokok']],
            ['type' => 2, 'jumlah_simpanan' => $data['wajib']],
            ['type' => 3, 'jumlah_simpanan' => $data['sukarela']],
        ];

        // dd($savings);
        // Loop dan simpan ke database
        // dd('Function executed');
        Simpanan::insert(array_map(function ($saving) use ($data) {
            return [
                'user_id' => $data['user_id'],
                'type' => $saving['type'],
                'jumlah_simpanan' => $saving['jumlah_simpanan'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $savings));

        return $data;
    }

}
