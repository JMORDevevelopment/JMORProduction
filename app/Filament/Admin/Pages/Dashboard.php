<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\DashboardStatsOverview;
use App\Filament\Admin\Widgets\LoginActivityWidget;
use App\Filament\Admin\Widgets\RecentOrdersWidget;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    public function getWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
            RecentOrdersWidget::class,
            LoginActivityWidget::class,
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('backup')
                ->label('Take Backup')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(fn () => $this->backup())
                ->requiresConfirmation()
                ->modalHeading('Database Backup')
                ->modalDescription('This will create a SQL backup file of the entire database.')
                ->modalSubmitActionLabel('Take Backup Now'),
        ];
    }

    /**
     * CI backup logic: exports all tables to a SQL file.
     */
    private function backup(): void
    {
        $folder = storage_path('app/backups');

        if (! is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $result = '';

        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];

            $query = DB::select("SELECT * FROM `{$tableName}`");
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");

            $result .= "DROP TABLE IF EXISTS `{$tableName}`;\n\n";
            $result .= $createTable[0]->{'Create Table'}.";\n\n";

            foreach ($query as $row) {
                $values = array_map(function ($value) {
                    if (is_null($value)) {
                        return 'NULL';
                    }

                    return "'".addslashes($value)."'";
                }, (array) $row);

                $result .= "INSERT INTO `{$tableName}` VALUES(".implode(', ', $values).");\n";
            }

            $result .= "\n\n";
        }

        $date = date('m-d-Y_h-i-sa');
        $filename = "{$folder}/Database_Of_JMOR_{$date}.sql";

        File::put($filename, $result);

        Notification::make()
            ->title('Backup created successfully')
            ->body('File: '.basename($filename))
            ->success()
            ->send();
    }
}
