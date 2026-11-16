<?php

namespace App\Filament\Admin\Resources\TalkShowResource\Pages;

use App\Filament\Admin\Resources\TalkShowResource;
use App\Mail\TalkShowStatusChanged;
use App\Models\TalkShow;
use App\Models\TalkShowAudit;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EditTalkShow extends EditRecord
{
    protected static string $resource = TalkShowResource::class;

    protected ?int $previousStatus = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->previousStatus = (int) $this->record->status;
    }

    protected function afterSave(): void
    {
        $newStatus = (int) $this->record->status;

        if ($this->previousStatus === null || $this->previousStatus === $newStatus) {
            return;
        }

        TalkShowAudit::create([
            'talk_show_id' => $this->record->id,
            'action' => 'status_changed',
            'old_status' => $this->previousStatus,
            'new_status' => $newStatus,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => 'Status changed from '.TalkShow::statusLabel($this->previousStatus)
                .' to '.TalkShow::statusLabel($newStatus).' by admin.',
            'created_at' => now(),
        ]);

        try {
            Mail::to($this->record->email)->send(new TalkShowStatusChanged($this->record, $newStatus));
        } catch (\Exception $e) {
            Log::error('Failed to send talk show status email: '.$e->getMessage());
        }
    }
}
