<?php

namespace App\Models;

use App\Enums\SupportTicketStatus;
use App\Observers\SupportTicketObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(SupportTicketObserver::class)]
class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'message',
        'status',
        'user_id',
        'phone',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'status' => SupportTicketStatus::class,
        ];
    }

    public function isOpen(): Attribute
    {
        return Attribute::get(fn(): bool => $this->status === SupportTicketStatus::OPEN);
    }

    public function isInProgress(): Attribute
    {
        return Attribute::get(fn(): bool => $this->status === SupportTicketStatus::IN_PROGRESS);
    }

    public function isResolved(): Attribute
    {
        return Attribute::get(fn(): bool => $this->status === SupportTicketStatus::RESOLVED);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', SupportTicketStatus::OPEN);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', SupportTicketStatus::IN_PROGRESS);
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->where('status', SupportTicketStatus::RESOLVED);
    }

    public function markAsOpen(): void
    {
        $this->status = SupportTicketStatus::OPEN;
        $this->save();
    }

    public function markAsInProgress(): void
    {
        $this->status = SupportTicketStatus::IN_PROGRESS;
        $this->save();
    }

    public function markAsResolved(): void
    {
        $this->status = SupportTicketStatus::RESOLVED;
        $this->save();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
