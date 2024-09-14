<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SupportTicket $ticket): bool
    {
        return self::isOwnTicket($user, $ticket);
    }

    public function create(User $user): bool
    {
        return true;
        // TODO: 
        // return $user->isCustomer || $user->isDriver;
    }

    public function update(User $user, SupportTicket $ticket): bool
    {
        return self::isOwnTicket($user, $ticket);
    }

    public function delete(User $user, SupportTicket $ticket): bool
    {
        return self::isOwnTicket($user, $ticket);
    }

    public static function isOwnTicket(User $user, SupportTicket $ticket): bool
    {
        return $user->id === $ticket->user_id;
    }
}
