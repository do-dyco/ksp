<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LedgerResource\Pages;
use App\Filament\Resources\LedgerResource\RelationManagers;
use App\Models\Journal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Filament\Tables\Columns\Summarizers\Sum;

class LedgerResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $label = "Ledger Book";
    protected static ?int $navigationSort = 3;

    public static function getMonthOptions(): array
    {
        return Journal::selectRaw("DATE_FORMAT(date, '%Y-%m') as bulan")
            ->distinct()
            ->orderBy('bulan', 'desc')
            ->pluck('bulan', 'bulan')
            ->map(fn($bulan) => Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y'))
            ->toArray();
    }

    public static function canCreate(): bool
{
    return false;
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Account')
                    ->sortable(),
                TextColumn::make('date')
                    ->sortable(),
                TextColumn::make('description'),
                TextColumn::make('debit')
                    ->money('IDR')
                    ->summarize(Sum::make()->label('Total Debit')),
                TextColumn::make('credit')
                    ->money('IDR')
                    ->summarize(Sum::make()->label('Total Credit')),
                    ])
                ->defaultGroup('accountz.jenis_transaksi', 'account')
                ->filters([
                    Filter::make('bulan')
                        ->form([
                            Select::make('bulan')
                                ->label('Pilih Bulan')
                                ->options(self::getMonthOptions())
                                ->searchable(),
                        ])
                        ->query(fn(Builder $query, array $data) =>
                            $query->when($data['bulan'] ?? null, function ($q, $bulan) {
                                [$year, $month] = explode('-', $bulan);
                                return $q->whereYear('date', $year)->whereMonth('date', $month);
                            })
                        ),
                ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLedgers::route('/'),
        ];
    }
}
