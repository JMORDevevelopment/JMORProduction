<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestimonialResource\Pages\CreateTestimonial;
use App\Filament\Admin\Resources\TestimonialResource\Pages\EditTestimonial;
use App\Filament\Admin\Resources\TestimonialResource\Pages\ListTestimonials;
use App\Models\Testimonial;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $recordTitleAttribute = 'service_used';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Testimonials';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 24;

    protected static ?string $modelLabel = 'Testimonial';

    protected static ?string $pluralModelLabel = 'Testimonials';

    protected static ?string $slug = 'testimonials';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Customer')
                    ->options(fn (): array => User::query()
                        ->orderBy('firstname')
                        ->get()
                        ->mapWithKeys(fn (User $user): array => [
                            $user->user_id => trim("{$user->firstname} {$user->lastname} ({$user->email})"),
                        ])
                        ->all())
                    ->searchable()
                    ->required(),

                TextInput::make('service_used')
                    ->label('Service Used')
                    ->required()
                    ->maxLength(255),

                Textarea::make('message')
                    ->label('Message')
                    ->rows(5)
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Approved',
                    ])
                    ->default(0)
                    ->required(),

                DateTimePicker::make('published')
                    ->label('Published')
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('customer.firstname')
                    ->label('Customer')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('service_used')
                    ->label('Service Used')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (string) $state === '0' ? 'Pending' : 'Approved')
                    ->color(fn ($state): string => (string) $state === '0' ? 'danger' : 'success'),

                TextColumn::make('published')
                    ->label('Published')
                    ->dateTime()
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
            'index' => ListTestimonials::route('/'),
            'create' => CreateTestimonial::route('/create'),
            'edit' => EditTestimonial::route('/{record}/edit'),
        ];
    }
}
