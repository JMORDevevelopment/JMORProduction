<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GiftCardResource\Pages\CreateGiftCard;
use App\Filament\Admin\Resources\GiftCardResource\Pages\EditGiftCard;
use App\Filament\Admin\Resources\GiftCardResource\Pages\ListGiftCards;
use App\Models\GiftCard;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GiftCardResource extends Resource
{
    protected static ?string $model = GiftCard::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-gift';

    protected static string|\UnitEnum|null $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Gift Cards';

    protected static ?string $modelLabel = 'Gift Card';

    protected static ?string $pluralModelLabel = 'Gift Cards';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gift Card Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->trim(),
                        TextInput::make('heading')
                            ->label('Heading')
                            ->required()
                            ->maxLength(255)
                            ->trim(),
                        Textarea::make('description')
                            ->label('Description')
                            ->trim(),
                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix('USD'),
                        TextInput::make('upfront')
                            ->label('Upfront Amount')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix('USD'),
                        TextInput::make('category')
                            ->label('Category')
                            ->maxLength(255)
                            ->trim(),
                        TextInput::make('coupon_number')
                            ->label('Coupon Number')
                            ->maxLength(255)
                            ->trim()
                            ->unique(ignoreRecord: true),
                        Toggle::make('status')
                            ->label('Active')
                            ->default(false),
                    ])->columns(2),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->disk('public_direct')
                            ->directory('uploads/gift_card')
                            ->maxSize(5120),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public_direct')
                    ->circular()
                    ->defaultImageUrl(asset('images/default-avatar.png')),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('heading')
                    ->label('Heading')
                    ->limit(50),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('coupon_number')
                    ->label('Coupon')
                    ->copyable(),
                IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGiftCards::route('/'),
            'create' => CreateGiftCard::route('/create'),
            'edit' => EditGiftCard::route('/{record}/edit'),
        ];
    }
}
