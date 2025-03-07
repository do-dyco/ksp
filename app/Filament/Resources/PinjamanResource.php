<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PinjamanResource\Pages;
use App\Filament\Resources\PinjamanResource\RelationManagers;
use App\Models\Jenis_pengajuan;
use App\Models\Pinjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;

class PinjamanResource extends Resource
{
    protected static ?string $model = Pinjaman::class;

    protected static ?string $navigationGroup = "Transaksi";

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        return $user->id === '1'
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->where('user_id', $user->id);
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     $user = auth()->id();
    //     $query = parent::getEloquentQuery();

    //     switch ($user->role) {
    //         case 'penanggung_jawab':
    //             return $query->where('penanggung_jawab_id', $user->id)
    //                 ->where('is_approve', 0);
    //         case 'atasan':
    //             return $query->where('is_approve', 1);
    //         case 'admin':
    //             return $query->where('is_approve', 2);
    //         default:
    //             return $query->where('id', 0);
    //     }
    // }

    protected static ?string $label = "Loan";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static function hitungTotalAngsuran($set, $state)
    {
        $jumlahPengajuan = $state['jumlah_pengajuan'] ?? 0;
        $tenor = $state['tenor'] ?? 0;
        $bunga = $state['bunga'] ?? 0;

        if ($jumlahPengajuan && $tenor && $bunga) {
            $totalAngsuran = ($jumlahPengajuan * (1 + ($bunga / 100))) / $tenor;
            $totalAngsuranBulat = round($totalAngsuran);
            $set('total_angsuran', $totalAngsuranBulat);
        } else {
            $set('total_angsuran', 0);
        }
    }

    public static function created($record)
    {
        $approver = User::find($record->penanggung_jawab_id);

        if ($approver && !$approver->hasRole('approver_level_1')) {
            $approver->assignRole('approver_level_1');
        }
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
                Hidden::make('user_id')
                    ->default(auth()->id()),

                Select::make('type')
                    ->autofocus()
                    ->options(Jenis_pengajuan::all()->pluck('jenis', 'id')->toArray())
                    ->required()
                    ->placeholder('Choose Loan Type'),

                TextInput::make('jumlah_pengajuan')
                    ->placeholder('Masukkan jumlah pengajuan pinjaman')
                    ->prefix('Rp')
                    ->live()
                    ->numeric()
                    ->required()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        self::hitungTotalAngsuran($set, $get());
                    }),

                Select::make('tenor')
                    ->options([
                        3 => '3 Bulan',
                        6 => '6 Bulan',
                        12 => '12 Bulan',
                        18 => '18 Bulan',
                        24 => '24 Bulan',
                        36 => '36 Bulan',
                        60 => '60 Bulan',
                    ])
                    ->live()
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        self::hitungTotalAngsuran($set, $get());
                    }),

                TextInput::make('bunga')
                    ->disabled()
                    ->default('1')
                    ->label('Rate Bunga')
                    ->placeholder('Masukkan jumlah pengajuan pinjaman')
                    ->suffix('%')
                    ->live()
                    ->numeric()
                    ->dehydrated(),

                TextInput::make('total_angsuran')
                    ->disabled()
                    ->prefix('Rp')
                    ->numeric()
                    ->live()
                    ->default('0')
                    ->dehydrated(),

                Select::make('penanggung_jawab')
                    ->autofocus()
                    ->options(User::all()->pluck('name', 'id')->toArray())
                    ->placeholder('Choose User')
                    ->required(),

                Hidden::make('is_approve')
                    ->default('0'),

                Hidden::make('status')
                    ->default('0'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                TextColumn::make('type')
                    ->formatStateUsing(fn ($state) => [
                        1 => 'Pembiayaan Koperasi Karyawan',
                        2 => 'Emergency',
                        3 => 'Sumbangan Ranap',
                    ][$state] ?? $state)
                    ->sortable(),

                TextColumn::make('jumlah_pengajuan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tenor')
                    ->label('Tenor (bulan)')
                    ->sortable(),

                TextColumn::make('total_angsuran')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('bunga')
                    ->label('Rate Bunga (%)')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label("Penanggung Jawab")
                    ->sortable(),

                TextColumn::make('is_approve')
                    ->label('Approval')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        '0' => 'Waiting Approval',
                        '1' => 'Approve by Penanggung Jawab',
                        '2' => 'Approve by Atasan',
                        '3' => 'Approve by Admin',
                        '4' => 'Rejected by Penanggung Jawab',
                        '5' => 'Rejected by Atasan',
                        '6' => 'Rejected by Admin',
                    })
                    ->color(fn ($state) => match ($state) {
                        '0' => 'warning',
                        '1' => 'success',
                        '2' => 'success',
                        '3' => 'success',
                        '4' => 'danger',
                        '5' => 'danger',
                        '6' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPinjamen::route('/'),
            'create' => Pages\CreatePinjaman::route('/create'),
            'edit' => Pages\EditPinjaman::route('/{record}/edit'),
        ];
    }
}
