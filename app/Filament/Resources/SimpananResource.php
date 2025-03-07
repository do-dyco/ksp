<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimpananResource\Pages;
use App\Filament\Resources\SimpananResource\RelationManagers;
use App\Models\Simpanan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;


class SimpananResource extends Resource
{
    protected static ?string $model = Simpanan::class;

    protected static ?string $navigationGroup = "Transaksi";

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        return $user->id == '1'
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->where('user_id', $user->id);
    }

    protected static ?string $label = "Saving";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Select::make('user_id')
                        ->label('Nama Anggota')
                        ->autofocus()
                        ->options(User::all()->pluck('name', 'id')->toArray())
                        ->placeholder('Choose User'),

                    TextInput::make('pokok')
                        ->label('Tabungan Pokok')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    TextInput::make('wajib')
                        ->label('Tabungan Wajib')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    TextInput::make('sukarela')
                        ->label('Tabungan Sukarela')
                        ->numeric()
                        ->default(0)
                        ->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),

                TextColumn::make('jumlah_simpanan')
                    ->money('IDR')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->searchable(),

            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListSimpanans::route('/'),
            'create' => Pages\CreateSimpanan::route('/create'),
            'edit' => Pages\EditSimpanan::route('/{record}/edit'),
        ];
    }
}
