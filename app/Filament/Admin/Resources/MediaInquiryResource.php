<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaInquiryResource\Pages\CreateMediaInquiry;
use App\Filament\Admin\Resources\MediaInquiryResource\Pages\EditMediaInquiry;
use App\Filament\Admin\Resources\MediaInquiryResource\Pages\ListMediaInquiries;
use App\Models\MediaInquiry;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaInquiryResource extends Resource
{
    protected static ?string $model = MediaInquiry::class;

    protected static ?string $recordTitleAttribute = 'media';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Media Inquiries';

    protected static string|\UnitEnum|null $navigationGroup = 'Inquiries';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Media Inquiry';

    protected static ?string $pluralModelLabel = 'Media Inquiries';

    protected static ?string $slug = 'media-inquiries';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('media')
                    ->label('Media')
                    ->required()
                    ->maxLength(100),

                TextInput::make('contact')
                    ->label('Contact')
                    ->maxLength(60),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(50),

                TextInput::make('phone')
                    ->label('Phone')
                    ->maxLength(60),

                TextInput::make('story_concept')
                    ->label('Story Concept')
                    ->maxLength(100),

                DatePicker::make('press_deadline')
                    ->label('Press Deadline'),

                Textarea::make('story_details')
                    ->label('Story Details')
                    ->rows(5),

                Textarea::make('best_contact')
                    ->label('Best Contact')
                    ->rows(3),

                Select::make('media_status')
                    ->label('Status')
                    ->options([
                        '0' => 'New',
                        '1' => 'Preview',
                    ])
                    ->default('0')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('media')
                    ->label('Media')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('contact')
                    ->label('Contact')
                    ->searchable(),

                TextColumn::make('media_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (string) $state === '0' ? 'New' : 'Preview')
                    ->color(fn ($state): string => (string) $state === '0' ? 'danger' : 'info'),

                TextColumn::make('press_deadline')
                    ->label('Deadline')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                EditAction::make(),
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
            'index' => ListMediaInquiries::route('/'),
            'create' => CreateMediaInquiry::route('/create'),
            'edit' => EditMediaInquiry::route('/{record}/edit'),
        ];
    }
}
