<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Admin\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use App\Models\UserGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'email';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static string|\UnitEnum|null $navigationGroup = 'Users';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static ?string $slug = 'users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('firstname')
                    ->label('First Name')
                    ->required()
                    ->maxLength(300),

                TextInput::make('lastname')
                    ->label('Last Name')
                    ->required()
                    ->maxLength(300),

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(96)
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->nullable(fn (string $operation): bool => $operation === 'edit')
                    ->minLength(8)
                    ->maxLength(255)
                    ->helperText('Leave blank on edit to keep the current password.')
                    ->dehydrateStateUsing(fn ($state): ?string => filled($state) ? bcrypt($state) : null),

                Select::make('user_group_id')
                    ->label('User Group')
                    ->options(fn (): array => UserGroup::orderBy('user_group_id')->pluck('name', 'user_group_id')->all())
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('firstname')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn ($record): string => $record->full_name),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('group.name')
                    ->label('Group')
                    ->placeholder('—'),

                TextColumn::make('date_added')
                    ->label('Registered')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('user_id', 'desc')
            ->filters([
                SelectFilter::make('user_group_id')
                    ->label('Group')
                    ->options(fn (): array => UserGroup::pluck('name', 'user_group_id')->all()),
            ])
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
