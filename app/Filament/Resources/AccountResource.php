<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationGroup = "Master";
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('code')
                    ->required(),

                TextInput::make('jenis_transaksi')
                    ->required(),

                Select::make('akun')
                    ->autofocus()
                    ->options([
                        'activa' => 'Activa',
                        'pasiva' => 'Pasiva'
                    ])
                    ->required(),

                    Select::make('pemasukan')
                    ->autofocus()
                    ->options([
                        '0' => 'Tidak',
                        '1' => 'Ya'
                    ])
                    ->live()
                    ->afterStateUpdated(fn ($set, $state) => $set('pengeluaran', $state === '1' ? '0' : '1'))
                    ->required(),

                Select::make('pengeluaran')
                    ->autofocus()
                    ->options([
                        '0' => 'Tidak',
                        '1' => 'Ya'
                    ])
                    ->live()
                    ->afterStateUpdated(fn ($set, $state) => $set('pemasukan', $state === '1' ? '0' : '1'))
                    ->required(),

                Select::make('status')
                    ->autofocus()
                    ->options([
                        '0' => 'Tidak Aktif',
                        '1' => 'Aktif'
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable(),
                TextColumn::make('jenis_transaksi')
                    ->sortable(),
                TextColumn::make('akun')
                    ->sortable(),
                TextColumn::make('pemasukan')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Tidak' : 'Ya')
                    ->sortable(),
                TextColumn::make('pengeluaran')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Tidak' : 'Ya')
                    ->sortable(),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => $state == 0 ? 'Tidak Aktif' : 'Aktif')
                    ->sortable(),
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
