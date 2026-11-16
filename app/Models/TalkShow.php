<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalkShow extends Model
{
    public const STATUS_SUBMITTED = 0;

    public const STATUS_UNDER_REVIEW = 1;

    public const STATUS_ACCEPTED = 2;

    public const STATUS_REJECTED = 3;

    protected $table = 'talk_show';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'last_name',
        'company_name',
        'phone',
        'email',
        'bio',
        'work_name',
        'work_detail',
        'weblink',
        'interview',
        'signature',
        'signed_pdf_path',
        'ip',
        'date_time',
        'status',
    ];

    public function audits(): HasMany
    {
        return $this->hasMany(TalkShowAudit::class, 'talk_show_id');
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public static function statusLabel(int $status): string
    {
        return self::statusLabels()[$status] ?? 'Unknown';
    }

    public static function statusOptions(): array
    {
        return self::statusLabels();
    }
}
