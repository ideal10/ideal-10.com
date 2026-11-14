<?php

namespace App\Providers;

use App\Models\User;
use App\Support\Audit;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerAuthAuditing();
    }

    /**
     * Sign-ins, sign-outs and failed attempts go to the audit log next to
     * content changes. A failed attempt keeps the email that was typed so
     * repeated guesses against one account are visible.
     */
    private function registerAuthAuditing(): void
    {
        Event::listen(Login::class, function (Login $event) {
            if ($event->user instanceof User) {
                Audit::record('login', $event->user, user: $event->user);
            }
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user instanceof User) {
                Audit::record('logout', $event->user, user: $event->user);
            }
        });

        Event::listen(Failed::class, function (Failed $event) {
            Audit::record(
                'login_failed',
                $event->user instanceof User ? $event->user : User::class,
                label: (string) ($event->credentials['email'] ?? ''),
            );
        });
    }
}
