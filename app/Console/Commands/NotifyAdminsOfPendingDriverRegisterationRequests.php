<?php

namespace App\Console\Commands;

use App\Models\Driver;
use App\Models\User;
use App\Notifications\AdminPendingDriverRegisterationRequestNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfPendingDriverRegisterationRequests extends Command
{
    protected $signature = 'notify:pending-driver-registeration-requests';

    protected $description = 'Notifies admins of driver registeration requests that have been under review for more than six hours';

    public function handle()
    {
        $this->info('Notifying admins of pending driver registeration requests...');

        $pending = Driver::underReview()
            ->where('created_at', '<=', now()->subHours(6))
            ->get();

        $this->info('Found ' . $pending->count() . ' pending driver registeration requests');

        $pending->each(function ($driver) {
            Notification::send(
                User::admins()->get(),
                new AdminPendingDriverRegisterationRequestNotification($driver),
            );
        });

        $this->info('Notified ' . $pending->count() . ' admins of pending driver registeration requests');
    }
}
