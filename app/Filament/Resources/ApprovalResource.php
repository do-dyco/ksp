<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalResource\Pages;
use App\Filament\Resources\ApprovalResource\RelationManagers;
use App\Models\Approval;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Pinjaman;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class ApprovalResource extends Resource
{
    protected static ?string $model = Approval::class;

    protected static ?string $navigationGroup = "Transaksi";

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        return $user->id === '1'
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->where('user_id', $user->id);
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengaju')
                    ->searchable(),

                TextColumn::make('jumlah_pengajuan')
                    ->money('IDR')
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
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn (Approval $record) => $record->status === '0')
                    ->action(fn (Approval $record) => $record->update(['is_approve' => 1])),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (Approval $record) => $record->status === '0')
                    ->action(fn (Approval $record) => $record->update(['is_approve' => 4])),

                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn (Approval $record) => $record->status === '0')
                    ->action(fn (Approval $record) => $record->update(['is_approve' => 2])),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (Approval $record) => $record->status === '0')
                    ->action(fn (Approval $record) => $record->update(['is_approve' => 5])),

                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn (Approval $record) => $record->status === '0')
                    ->action(fn (Approval $record) => $record->update(['is_approve' => 3])),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (Approval $record) => $record->status === '0')
                    ->action(fn (Approval $record) => $record->update(['is_approve' => 6])),

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
            'index' => Pages\ListApprovals::route('/'),
            'create' => Pages\CreateApproval::route('/create'),
            'edit' => Pages\EditApproval::route('/{record}/edit'),
        ];
    }
}
