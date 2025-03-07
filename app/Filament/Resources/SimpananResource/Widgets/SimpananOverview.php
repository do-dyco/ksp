<?php

namespace App\Filament\Resources\SimpananResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Simpanan;
use Illuminate\Support\Facades\Auth;

class SimpananOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $user = Auth::user();

        // Jika user admin (id = 1), hitung semua simpanan, jika bukan hanya simpanan miliknya
        $query = $user->id === 1 ? Simpanan::query() : Simpanan::where('user_id', $user->id);

        // Hitung total simpanan berdasarkan type
        $simpananByType = $query->selectRaw('type, SUM(jumlah_simpanan) as total')
            ->groupBy('type')
            ->pluck('total', 'type'); // Mengembalikan array [type => total]

        // Ambil simpanan terbaru berdasarkan type
        $latestSimpananByType = Simpanan::where('user_id', $user->id)
            ->latest()
            ->get()
            ->groupBy('type');

        // Definisi label type
        $typeLabels = [
            1 => 'Tabungan Pokok',
            2 => 'Tabungan Wajib',
            3 => 'Tabungan Sukarela',
        ];

        $cards = [];

        // Buat kartu berdasarkan type
        foreach ($typeLabels as $type => $label) {
            $total = $simpananByType[$type] ?? 0;


            $cards[] = Card::make("$label", 'IDR ' . number_format($total, 0, ',', '.'))
                ->description("Total $label recorded")
                ->color('success')
                ->icon('heroicon-o-banknotes');


        }

        foreach ($typeLabels as $type => $label) {
        $latest = isset($latestSimpananByType[$type]) ? number_format($latestSimpananByType[$type]->first()->jumlah_simpanan, 0, ',', '.') : '0';

        $cards[] = Card::make("Simpanan Terbaru - $label", 'IDR ' . $latest)
                ->description("Most recent $label entry")
                ->color('info')
                ->icon('heroicon-o-clock');
        }

        return $cards;
    }

}
