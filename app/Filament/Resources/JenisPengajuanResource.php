<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisPengajuanResource\Pages;
use App\Filament\Resources\JenisPengajuanResource\RelationManagers;
use App\Models\Jenis_pengajuan;
use App\Models\JenisPengajuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class JenisPengajuanResource extends Resource
{
    protected static ?string $model = Jenis_pengajuan::class;

    protected static ?string $navigationGroup = "Master";

    protected static ?string $label = "Jenis Pengajuan";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('jenis')
                    ->label('Nama Jenis Pengajuan')
                    ->required(),

                FileUpload::make('file')
                    ->label('Upload File')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis')
                    ->label('Nama Jenis Pengajuan')
                    ->searchable(),

                TextColumn::make('file')
                    ->label('Lihat File')
                    ->url(fn ($record) => asset('storage/' . $record->file))
                    ->openUrlInNewTab(),

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
            'index' => Pages\ListJenisPengajuans::route('/'),
            'create' => Pages\CreateJenisPengajuan::route('/create'),
            'edit' => Pages\EditJenisPengajuan::route('/{record}/edit'),
        ];
    }
}
