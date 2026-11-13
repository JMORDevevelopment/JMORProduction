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
use Filament\Forms\Components\Toggle;
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
                    ->placeholder('No linked customer account'),

                TextInput::make('display_name')
                    ->label('Public attribution')
                    ->maxLength(100)
                    ->helperText('Use only the name the customer approved for publication, such as “Jane D., Mahwah resident”.'),

                TextInput::make('service_used')
                    ->label('Service Used')
                    ->required()
                    ->maxLength(255),

                Textarea::make('message')
                    ->label('Message')
                    ->rows(5)
                    ->required(),

                Toggle::make('consent_to_publish')
                    ->label('Customer consented to publication')
                    ->helperText('Testimonials are hidden from the website until consent is recorded.'),

                DateTimePicker::make('verified_at')
                    ->label('Verified at')
                    ->helperText('Record when the testimonial and customer details were checked.'),

                TextInput::make('source_url')
                    ->label('Verification source')
                    ->maxLength(2048)
                    ->helperText('Optional link to the approved source or signed consent record.'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Approved',
                    ])
                    ->default(0)
                    ->helperText('Public only when approved, verified, and publication consent is recorded.')
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

                TextColumn::make('display_name')
                    ->label('Public name')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('service_used')
                    ->label('Service Used')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('consent_to_publish')
                    ->label('Consent')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Recorded' : 'Missing')
                    ->color(fn ($state): string => $state ? 'success' : 'danger'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (string) $state === '0' ? 'Pending' : 'Approved')
                    ->color(fn ($state): string => (string) $state === '0' ? 'danger' : 'success'),

                TextColumn::make('verified_at')
                    ->label('Verified')
                    ->dateTime()
                    ->placeholder('Not verified')
                    ->sortable(),

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
