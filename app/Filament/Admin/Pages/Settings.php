<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Cache;

class Settings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Settings';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 100;

    protected static ?string $title = 'Settings';

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all();

        $formData = [];
        foreach ($settings as $setting) {
            $formData[$setting->option] = $setting->value;
        }

        $this->form->fill($formData);
    }

    public function form(Schema $form): Schema
    {
        $settings = Setting::all();

        $components = [];
        foreach ($settings as $setting) {
            $label = str_replace('_', ' ', ucfirst($setting->option));

            $components[] = TextInput::make($setting->option)
                ->label($label)
                ->default($setting->value)
                ->maxLength(255)
                ->trim()
                ->autocomplete(false);
        }

        return $form
            ->components([
                Form::make($components)
                    ->id('settings-form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Update Settings')
                                ->submit('save')
                                ->color('primary')
                                ->icon('heroicon-o-check'),
                        ])
                            ->alignment(Alignment::End),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $option => $value) {
            Setting::where('option', $option)
                ->update(['value' => trim($value)]);
        }

        Cache::forget('settings.keyed');

        Notification::make()
            ->title('Settings updated successfully')
            ->success()
            ->send();
    }
}
