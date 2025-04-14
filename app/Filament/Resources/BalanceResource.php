<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BalanceResource\Pages;
use App\Models\Journal;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\Summarizers\Sum;

class BalanceResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $label = "Balance Sheet";
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

    public static function table(Table $table): Table
    {
        return $table
        ->query(
            fn () => \App\Models\Journal::query()
                ->join('accounts', 'journals.account', '=', 'accounts.id')
                ->select(
                    DB::raw('MAX(journals.id) as id'),
                    'accounts.akun as account_name',
                    'accounts.jenis_transaksi as jenis_transaksi',
                    DB::raw('SUM(journals.debit) as debit'),
                    DB::raw('SUM(journals.credit) as credit')
                )
                ->groupBy('accounts.akun', 'accounts.jenis_transaksi', 'journals.account')
        )
        ->defaultGroup('jenis_transaksi')
        ->columns([
            TextColumn::make('account_name')
    ->label('Nama Akun')
    ->sortable()
    ->searchable(),

TextColumn::make('jenis_transaksi')
    ->label('Jenis Transaksi')
    ->sortable(),

            TextColumn::make('debit')
                ->label('Debit')
                ->money('IDR', true)
                ->sortable()
                ->searchable()
                ->summarize(Sum::make()->label('Total Debit')),

            TextColumn::make('credit')
                ->label('Credit')
                ->money('IDR', true)
                ->sortable()
                ->searchable()
                ->summarize(Sum::make()->label('Total Credit')),
        ])

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
            'index' => Pages\ListBalances::route('/'),
        ];
    }
}
