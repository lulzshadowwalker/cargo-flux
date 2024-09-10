<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    use HasFactory;


    protected $fillable = [
        'language',
        'email_notifications',
        'sms_notifications',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'user_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
