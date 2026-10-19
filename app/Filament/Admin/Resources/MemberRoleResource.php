<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MemberRoleResource\Pages\CreateMemberRole;
use App\Filament\Admin\Resources\MemberRoleResource\Pages\EditMemberRole;
use App\Filament\Admin\Resources\MemberRoleResource\Pages\ListMemberRoles;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Website-member roles (web guard). Panel admin roles are managed on
 * the separate Filament Shield -> Roles screen.
 */
class MemberRoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Member Roles';

    protected static string|\UnitEnum|null $navigationGroup = 'Users';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Member Role';

    protected static ?string $pluralModelLabel = 'Member Roles';

    protected static ?string $slug = 'member-roles';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Role Name')
                                    ->unique(
                                        ignoreRecord: true,
                                        modifyRuleUsing: fn ($rule) => $rule->where('guard_name', 'web')
                                    )
                                    ->required()
                                    ->maxLength(255),

                                Hidden::make('guard_name')
                                    ->default('web'),

                                CheckboxList::make('permission_ids')
                                    ->label('Permissions')
                                    ->options(fn (): array => Permission::where('guard_name', 'web')
                                        ->orderBy('name')
                                        ->get()
                                        ->mapWithKeys(fn (Permission $permission): array => [
                                            $permission->getKey() => Str::headline($permission->name),
                                        ])
                                        ->all())
                                    ->default(fn (?Role $record): array => $record?->permissions->pluck('id')->all() ?? [])
                                    ->helperText('Tick exactly what members with this role may do on the website.')
                                    ->columns(2),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role')
                    ->weight(FontWeight::Medium)
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->searchable(),

                TextColumn::make('permissions_count')
                    ->badge()
                    ->label('Permissions')
                    ->counts('permissions')
                    ->color('primary'),

                TextColumn::make('users_count')
                    ->badge()
                    ->label('Members')
                    ->counts('users')
                    ->color('gray'),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMemberRoles::route('/'),
            'create' => CreateMemberRole::route('/create'),
            'edit' => EditMemberRole::route('/{record}/edit'),
        ];
    }

    /**
     * This screen manages website-member (web guard) roles only; panel
     * admin roles live on the Filament Shield -> Roles screen.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('guard_name', 'web');
    }
}
