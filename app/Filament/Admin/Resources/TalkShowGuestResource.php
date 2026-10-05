<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TalkShowGuestResource\Pages\EditTalkShowGuest;
use App\Filament\Admin\Resources\TalkShowGuestResource\Pages\ListTalkShowGuests;
use App\Mail\TalkShowAcceptance;
use App\Mail\TalkShowRevision;
use App\Models\TalkShow;
use App\Models\TalkShowTemplate;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Talk show guest applicants inbox.
 *
 * Original CI: admin/Talk_show_guests (index + pending pages — two identical
 * lists merged here into one list with a status filter; accept, reject,
 * revision, edit, delete actions; SMTP and calendar pages handled elsewhere).
 */
class TalkShowGuestResource extends Resource
{
    protected static ?string $model = TalkShow::class;

    protected static ?string $slug = 'talk-show-guests';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationLabel = 'Talk Show Guests';

    protected static string|\UnitEnum|null $navigationGroup = 'Talk Show';

    protected static ?int $navigationSort = 25;

    protected static ?string $modelLabel = 'Guest Applicant';

    protected static ?string $pluralModelLabel = 'Guest Applicants';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('First Name')
                    ->required(),

                TextInput::make('last_name')
                    ->label('Last Name'),

                TextInput::make('company_name')
                    ->label('Company Name'),

                TextInput::make('phone')
                    ->label('Phone'),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Accepted',
                    ])
                    ->default(0),

                TextInput::make('interview')
                    ->label('Interview Pitch'),

                Textarea::make('bio')
                    ->label('Bio')
                    ->rows(6),

                TextInput::make('ip')
                    ->label('IP')
                    ->disabled(),

                TextInput::make('date_time')
                    ->label('Submitted')
                    ->disabled(),
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
                    ->sortable(),

                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (string) $state === '1' ? 'Accepted' : 'Pending')
                    ->color(fn ($state): string => (string) $state === '1' ? 'success' : 'gray'),

                TextColumn::make('date_time')
                    ->label('Submitted')
                    ->sortable(),

                TextColumn::make('ip')
                    ->label('IP'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Accepted',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (TalkShow $record): bool => (int) $record->status !== 1)
                    ->action(function (TalkShow $record): void {
                        $record->update(['status' => 1]);

                        try {
                            Mail::to($record->email)->send(new TalkShowAcceptance($record));
                        } catch (\Throwable $e) {
                            Log::error('Failed to send talk show acceptance email: '.$e->getMessage());
                        }

                        Notification::make()
                            ->title('Applicant accepted')
                            ->success()
                            ->send();
                    }),
                Action::make('reopen')
                    ->label('Mark Pending')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('gray')
                    ->visible(fn (TalkShow $record): bool => (int) $record->status === 1)
                    ->action(function (TalkShow $record): void {
                        $record->update(['status' => 0]);

                        Notification::make()
                            ->title('Applicant marked as pending')
                            ->success()
                            ->send();
                    }),
                Action::make('revision')
                    ->label('Send Revision')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->form([
                        Select::make('template_id')
                            ->label('Template')
                            ->options(fn (): array => TalkShowTemplate::query()->pluck('name', 'id')->all())
                            ->live()
                            ->afterStateUpdated(function ($set, $state): void {
                                $set('content', TalkShowTemplate::query()->find($state)?->content ?? '');
                            }),
                        Textarea::make('content')
                            ->label('Message')
                            ->required()
                            ->rows(8),
                    ])
                    ->action(function (array $data, TalkShow $record): void {
                        try {
                            Mail::to($record->email)->send(new TalkShowRevision($record, $data['content']));
                        } catch (\Throwable $e) {
                            Log::error('Failed to send talk show revision email: '.$e->getMessage());

                            Notification::make()
                                ->title('Could not send the message')
                                ->danger()
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->title('Message sent to '.$record->email)
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
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
            'index' => ListTalkShowGuests::route('/'),
            'edit' => EditTalkShowGuest::route('/{record}/edit'),
        ];
    }
}
