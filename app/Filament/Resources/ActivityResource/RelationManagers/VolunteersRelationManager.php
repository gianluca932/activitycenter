<?php

namespace App\Filament\Resources\ActivityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VolunteersRelationManager extends RelationManager
{
    protected static string $relationship = 'volunteers';

    protected static ?string $title = 'Volontari impiegati';

    // ❌ IMPORTANT: disabilita "New volunteer"
    public function canCreate(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        // Questo form serve quando editi i campi pivot (art39, role, hours_on_activity)
        return $form->schema([
            Forms\Components\Toggle::make('art39')
                ->label('Art. 39')
                ->default(false),

     
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // colonne del Volunteer
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Cognome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tax_code')
                    ->label('Codice Fiscale')
                    ->searchable(),

                Tables\Columns\TextColumn::make('base.name')
                    ->label('Sede')
                    ->sortable()
                    ->toggleable(),

                // colonna pivot
                Tables\Columns\IconColumn::make('pivot.art39')
                    ->label('Art.39')
                    ->boolean()
                    ->sortable(),

            ])
            ->headerActions([
                // ✅ qui aggiungi volontari esistenti
                Tables\Actions\AttachAction::make()
                    ->label('Aggiungi volontario')
                    ->recordSelectSearchColumns(['first_name', 'last_name', 'tax_code'])
                    ->recordTitle(fn ($record) => "{$record->last_name} {$record->first_name} ({$record->tax_code})")
                    ->preloadRecordSelect()
                    ->form(function (Tables\Actions\AttachAction $action): array {
                        return [
                            $action->getRecordSelect(),

                            Forms\Components\Toggle::make('art39')
                                ->label('Art. 39')
                                ->default(false),
                        ];
                    }),
            ])
            ->actions([
                // ✅ edita campi pivot (art39 ecc.)
                Tables\Actions\EditAction::make()
                    ->label('Modifica'),

                // ✅ rimuove associazione dalla activity (non cancella il volontario)
                Tables\Actions\DetachAction::make()
                    ->label('Rimuovi'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()->label('Rimuovi selezionati'),
            ]);
    }
}
