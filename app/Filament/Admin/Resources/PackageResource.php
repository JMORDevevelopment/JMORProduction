<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PackageResource\Pages\CreatePackage;
use App\Filament\Admin\Resources\PackageResource\Pages\EditPackage;
use App\Filament\Admin\Resources\PackageResource\Pages\ListPackages;
use App\Models\Category;
use App\Models\Package;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Packages';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 14;

    protected static ?string $modelLabel = 'Package';

    protected static ?string $pluralModelLabel = 'Packages';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Short Title')
                    ->required()
                    ->maxLength(255)
                    ->trim()
                    ->columnSpanFull(),

                TextInput::make('heading')
                    ->label('Full Title')
                    ->required()
                    ->maxLength(255)
                    ->trim(),

                TextInput::make('priority')
                    ->label('Priority')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),

                TextInput::make('discount')
                    ->label('Discount (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0),

                Select::make('category_name')
                    ->label('Category')
                    ->options(fn () => Category::pluck('name', 'link'))
                    ->required()
                    ->searchable(),

                Toggle::make('status')
                    ->label('Active')
                    ->default(true),

                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('Thumbnail')
                    ->directory('uploads/packages')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                Repeater::make('serverPrices')
                    ->label('Server Plan Pricing')
                    ->schema([
                        TextInput::make('pack_price')
                            ->label('Price')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$'),

                        TextInput::make('from_qty')
                            ->label('From Qty')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('to_qty')
                            ->label('To Qty')
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->columns(3)
                    ->addActionLabel('Add Server Price Tier')
                    ->columnSpanFull(),

                Repeater::make('systemPrices')
                    ->label('Workstation Plan Pricing')
                    ->schema([
                        TextInput::make('system_price')
                            ->label('Price')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$'),

                        TextInput::make('from_qty')
                            ->label('From Qty')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('to_qty')
                            ->label('To Qty')
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->columns(3)
                    ->addActionLabel('Add Workstation Price Tier')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('heading')
                    ->label('Heading')
                    ->limit(40),

                TextColumn::make('category_name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),

                TextColumn::make('discount')
                    ->label('Discount')
                    ->suffix('%'),

                IconColumn::make('status')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('priority')
            ->reorderable('priority')
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPackages::route('/'),
            'create' => CreatePackage::route('/create'),
            'edit' => EditPackage::route('/{record}/edit'),
        ];
    }
}
