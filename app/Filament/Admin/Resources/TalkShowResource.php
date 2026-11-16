<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TalkShowResource\Pages\EditTalkShow;
use App\Filament\Admin\Resources\TalkShowResource\Pages\ListTalkShows;
use App\Models\TalkShow;
use App\Models\TalkShowSetting;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class TalkShowResource extends Resource
{
    protected static ?string $model = TalkShow::class;

    protected static ?string $recordTitleAttribute = 'email';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationLabel = 'Talk Show Applications';

    protected static string|\UnitEnum|null $navigationGroup = 'Inquiries';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Talk Show Application';

    protected static ?string $pluralModelLabel = 'Talk Show Applications';

    protected static ?string $slug = 'talk-show-applications';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('First Name')
                    ->disabled(),

                TextInput::make('last_name')
                    ->label('Last Name')
                    ->disabled(),

                TextInput::make('company_name')
                    ->label('Company')
                    ->disabled(),

                TextInput::make('email')
                    ->label('Email')
                    ->disabled(),

                TextInput::make('phone')
                    ->label('Phone')
                    ->disabled(),

                Textarea::make('bio')
                    ->label('Bio')
                    ->rows(4)
                    ->disabled(),

                Textarea::make('interview')
                    ->label('Interview Pitch Concept')
                    ->rows(4)
                    ->disabled(),

                TextInput::make('signature')
                    ->label('Electronic Signature')
                    ->disabled(),

                TextInput::make('signed_pdf_path')
                    ->label('Signed PDF')
                    ->disabled()
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Stored' : 'Not generated'),

                Select::make('status')
                    ->label('Status')
                    ->options(TalkShow::statusOptions())
                    ->default(TalkShow::STATUS_SUBMITTED)
                    ->required()
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn ($record): string => trim("{$record->name} {$record->last_name}")),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('signature')
                    ->label('Signed')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Yes' : 'No')
                    ->color(fn (?string $state): string => filled($state) ? 'success' : 'gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => TalkShow::statusLabel((int) $state))
                    ->color(fn ($state): string => match ((int) $state) {
                        TalkShow::STATUS_SUBMITTED => 'info',
                        TalkShow::STATUS_UNDER_REVIEW => 'warning',
                        TalkShow::STATUS_ACCEPTED => 'success',
                        TalkShow::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('date_time')
                    ->label('Submitted')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                EditAction::make(),
                Action::make('downloadSignedPdf')
                    ->label('Signed PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record): ?string => filled($record->signed_pdf_path)
                        ? route('admin.talk_show.signed_pdf', $record)
                        : null)
                    ->visible(fn ($record): bool => filled($record->signed_pdf_path)),
            ])
            ->headerActions([
                Action::make('uploadAgreement')
                    ->label('Upload Guest Agreement')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('primary')
                    ->form([
                        FileUpload::make('agreement')
                            ->label('Agreement PDF')
                            ->disk('public')
                            ->directory('talk-show')
                            ->acceptedFileTypes(['application/pdf'])
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $settings = TalkShowSetting::query()->firstOrCreate([], [
                            'price' => '',
                            'question' => '',
                        ]);
                        $settings->agreement_path = $data['agreement'];
                        $settings->save();

                        Notification::make()
                            ->title('Guest agreement uploaded')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTalkShows::route('/'),
            'edit' => EditTalkShow::route('/{record}/edit'),
        ];
    }

    /**
     * Resolve the currently uploaded guest agreement URL for the public form.
     */
    public static function agreementUrl(): ?string
    {
        $path = TalkShowSetting::query()->first()?->agreement_path;

        if (blank($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
