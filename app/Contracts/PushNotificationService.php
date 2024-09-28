<?php

namespace App\Contracts;

use App\Enums\Audience;
use App\Models\User;

interface PushNotificationService
{
    public static function make(): self;

    public function title(string $title): self;

    public function body(string $body): self;

    /**
    * @param User|array<User>|Audience $notifiable
    *
    * @return self
    */
    public function to($notifiable): self;

    public function send(): void;
}
