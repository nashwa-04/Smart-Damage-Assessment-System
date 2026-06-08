<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationModel extends Model
{
    use HasFactory;

    protected $table = 'notifications_custom';

    protected $fillable = [
        'user_id',
        'report_id',
        'type',
        'title_ar',
        'title_en',
        'message_ar',
        'message_en',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public static function createForUser(int $userId, string $type, string $titleAr, string $titleEn, string $messageAr, string $messageEn, ?int $reportId = null): self
    {
        return self::create([
            'user_id' => $userId,
            'report_id' => $reportId,
            'type' => $type,
            'title_ar' => $titleAr,
            'title_en' => $titleEn,
            'message_ar' => $messageAr,
            'message_en' => $messageEn,
            'is_read' => false,
        ]);
    }
}
