<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalResource\Pages;
use App\Filament\Resources\JournalResource\RelationManagers;
use App\Models\Account;
use App\Models\Journal;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $navigationGroup = "Transaksi";

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->required()
                    ->format('Y-m-d')
                    ->default(now()),

                TextInput::make('description')
                    ->required(),

                Select::make('account')
                    ->autofocus()
                    ->options(Account::all()->pluck('jenis_transaksi', 'id')->toArray())
                    ->required(),

                TextInput::make('debit')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('credit')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accountz.jenis_transaksi')
                    ->sortable(),

                TextColumn::make('description')
                    ->sortable(),

                TextColumn::make('debit')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('credit')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('date')
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
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
}
