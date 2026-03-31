<?php

namespace App\Filament\Resources;

use App\Exports\VolunteersTemplateExport;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Resources\VolunteerResource\Pages;
use App\Models\Volunteer;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;


class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static ?string $modelLabel = 'Volontario';
    protected static ?string $pluralModelLabel = 'Volontari';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fullname')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('luogo_di_nascita')
                    ->label('Luogo di Nascita')
                    ->maxLength(255),

                Forms\Components\TextInput::make('numero_iscrizione_regionale')
                    ->label('N. Iscrizione Regionale')
                    ->maxLength(255),

                Forms\Components\TextInput::make('residenza')
                    ->label('Residenza')
                    ->maxLength(255),

                Forms\Components\TextInput::make('cellulare')
                    ->label('Cellulare')
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),

                Forms\Components\Textarea::make('patenti')
                    ->label('Patenti')
                    ->rows(3),

                Forms\Components\TextInput::make('tax_code')
                    ->label('Codice Fiscale')
                    ->required()
                    ->maxLength(16),

                // ✅ Sede (Base) come relazione
                Forms\Components\Select::make('base_id')
                    ->label('Sede')
                    ->relationship('base', 'name')
                    ->searchable()
                    ->preload()
                    // permette di creare/modificare la sede direttamente dal form
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome sede')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Codice')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('city')
                            ->label('Città')
                            ->maxLength(255),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome sede')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Codice')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('city')
                            ->label('Città')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fullname')
                    ->label('Nome Completo')
                    ->searchable(),

                Tables\Columns\TextColumn::make('luogo_di_nascita')
                    ->label('Luogo di Nascita')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('numero_iscrizione_regionale')
                    ->label('N. Iscr. Reg.')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('residenza')
                    ->label('Residenza')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('cellulare')
                    ->label('Cellulare')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('patenti')
                    ->label('Patenti')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tax_code')
                    ->label('Codice Fiscale')
                    ->searchable(),

                // ✅ mostra il nome della sede dalla relazione
                Tables\Columns\TextColumn::make('base.name')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_template')
                    ->label('Scarica Template Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function () {
                        return Excel::download(new VolunteersTemplateExport(), 'template_volontari.xlsx');
                    }),
                Tables\Actions\Action::make('import')
                    ->label('Importa da Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('base_id')
                            ->label('Sede di Appartenenza')
                            ->relationship('base', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\FileUpload::make('file')
                            ->label('File Excel')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                            ->required(),
                    ])
                    ->modalDescription('Seleziona la sede e carica un file Excel con i dati dei volontari.')
                    ->action(function (array $data) {
                        $filePath = storage_path('app/public/' . $data['file']);
                        $baseId = $data['base_id'];

                        // Importa il file
                        $import = new VolunteersImport($baseId);
                        Excel::import($import, $filePath);

                        $body = "Importati: {$import->importedCount}. \n\n Skippati: {$import->skippedCount}.";

                        if (! empty($import->errors)) {
                            $body .= "\n\n" . implode("\n", array_slice($import->errors, 0, 5));

                            if (count($import->errors) > 5) {
                                $body .= "\n...and more.";
                            }
                        }

                        // Notifica di successo con dettagli
                        Notification::make()
                            ->title('Importazione completata')
                            ->body($body)
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Importa Volontari da Excel')
                    ->modalSubmitActionLabel('Importa'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Modifica'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Elimina'),
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
            'index' => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit' => Pages\EditVolunteer::route('/{record}/edit'),
        ];
    }
}
