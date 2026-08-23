<?php

namespace App\Filament\Admin\Widgets;

use App\Models\LogHistory;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LoginActivityWidget extends TableWidget
{
    protected static ?string $heading = 'Login Activity';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LogHistory::orderByDesc('id')->limit(20)
            )
            ->columns([
                TextColumn::make('user_name')
                    ->label('User')
                    ->getStateUsing(function ($record) {
                        $user = User::where('user_id', $record->user_id)->first();

                        return trim(($user->firstname ?? '').' '.($user->lastname ?? ''));
                    }),

                TextColumn::make('ip')
                    ->label('IP Address'),

                TextColumn::make('log_time')
                    ->label('Time')
                    ->formatStateUsing(fn ($state) => $state ? date('m-d-Y H:i:s', strtotime($state)) : ''),
            ])
            ->paginated(false);
    }
}
