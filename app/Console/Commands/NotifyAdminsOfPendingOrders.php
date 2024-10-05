<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminPendingOrderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfPendingOrders extends Command
{
    protected $signature = 'notify:pending';

    protected $description = 'Notifies admins of orders that are pending driver assignment';

    public function handle()
    {
        $this->info('Notifying admins of pending orders...');

        $pending = Order::whereStatus(OrderStatus::PENDING_DRIVER_ASSIGNMENT)
            ->where('created_at', '<=', now()->subHours(6))
            ->get();

        $this->info('Found ' . $pending->count() . ' pending orders');

        $pending->each(function ($order) {
            Notification::send(
                User::admins()->get(),
                new AdminPendingOrderNotification($order),
            );
        });

        $this->info('Notified ' . $pending->count() . ' admins of pending orders');
    }
}
