<?php

namespace App\Listeners\Tenants;

use App\Events\Tenants\TenantCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Tenants\EmailToSetPasswordTenants;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailToSetPasswordTenant
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantCreated $event): void
    {
        tenancy()->initialize($event->tenantId);

        $user = User::where('email', $event->email)->first();

        $email = new EmailToSetPasswordTenants(
            $event->name,
            $event->email,
            $event->time,
            $event->token,
            $event->title,
            $event->tenantId,
            $event->domain
        );
        Mail::to($user)->send($email);
    }
}
