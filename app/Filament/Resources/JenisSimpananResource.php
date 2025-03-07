<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisSimpananResource\Pages;
use App\Filament\Resources\JenisSimpananResource\RelationManagers;
use App\Models\Jenis_simpanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisSimpananResource extends Resource
{
    protected static ?string $model = Jenis_simpanan::class;

    protected static ?string $navigationGroup = "Master";

    protected static ?string $label = "Jenis Simpanan";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_simpanan')
                ->label('Nama Jenis Simpanan')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_simpanan')
                ->searchable(),
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
            'index' => Pages\ListJenisSimpanans::route('/'),
            'create' => Pages\CreateJenisSimpanan::route('/create'),
            'edit' => Pages\EditJenisSimpanan::route('/{record}/edit'),
        ];
    }
}
