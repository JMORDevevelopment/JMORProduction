<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminUserResource\Pages\CreateAdminUser;
use App\Filament\Admin\Resources\AdminUserResource\Pages\EditAdminUser;
use App\Filament\Admin\Resources\AdminUserResource\Pages\ListAdminUsers;
use App\Models\Admin;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AdminUserResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $recordTitleAttribute = 'email';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Admin Accounts';

    protected static string|\UnitEnum|null $navigationGroup = 'Users';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Admin Account';

    protected static ?string $pluralModelLabel = 'Admin Accounts';

    protected static ?string $slug = 'admin-users';

    /**
     * Managing admin accounts (roles, status, credentials) is restricted to
     * full admins (role = 1) — consistent with the panel's role gating.
     */
    public static function canViewAny(): bool
    {
        return auth('admin')->user()?->isAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('firstname')
                    ->label('First Name')
                    ->required()
                    ->maxLength(30),

                TextInput::make('lastname')
                    ->label('Last Name')
                    ->required()
                    ->maxLength(50),

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(30)
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

                Select::make('role')
                    ->label('Role')
                    ->options([
                        1 => 'Admin',
                        0 => 'Simple user',
                    ])
                    ->default(1)
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Public',
                        0 => 'Banned',
                    ])
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('admin_id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('firstname')
                    ->label('Name')
                    ->getStateUsing(fn ($record): string => trim("{$record->firstname} {$record->lastname}")),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (int) $state === 1 ? 'Admin' : 'Simple user')
                    ->color(fn ($state): string => (int) $state === 1 ? 'danger' : 'info'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (int) $state === 1 ? 'Public' : 'Banned')
                    ->color(fn ($state): string => (int) $state === 1 ? 'success' : 'danger'),

                TextColumn::make('last_login')
                    ->label('Last Login')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('admin_id', 'desc')
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
            'index' => ListAdminUsers::route('/'),
            'create' => CreateAdminUser::route('/create'),
            'edit' => EditAdminUser::route('/{record}/edit'),
        ];
    }
}
