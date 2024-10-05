<?php

namespace App\Console\Commands;

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminPendingDirectPaymentOrderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfPendingDirectPaymentOrders extends Command
{
    protected $signature = 'notify:pending-direct-payment-orders';

    protected $description = 'Notifies admins of orders that have been made with direct payment and still are neither approved nor rejected';

    public function handle()
    {
        $this->info('Notifying admins of pending direct payment orders...');

        $pending = Order::where('payment_method', OrderPaymentMethod::DIRECT)
            ->where('payment_status', OrderPaymentStatus::PENDING_APPROVAL)
            ->where('created_at', '<=', now()->subHours(2))
            ->get();

        $this->info('Found ' . $pending->count() . ' pending direct payment orders');

        $pending->each(function ($order) {
            Notification::send(
                User::admins()->get(),
                new AdminPendingDirectPaymentOrderNotification($order),
            );
        });

        $this->info('Notified ' . $pending->count() . ' admins of pending direct payment orders');
    }
}
