<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\RegistrationAlert' => [
            'App\Listeners\SendEmailAlert',
        ],
        'App\Events\ResetPasswordAlert' => [
            'App\Listeners\SendResetPasswordEmailAlert',
        ],
        'App\Events\ContactUsAlert' => [
            'App\Listeners\SendContactUsEmailAlert',
        ],                      
        'App\Events\OnlineTestResultAlert' => [
            'App\Listeners\SendOnlineTestResultToUserEmail',
        ],
        'App\Events\NewExamNotificationAlert' => [
            'App\Listeners\SendNotificationForNewExamAlert',
        ],                        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
