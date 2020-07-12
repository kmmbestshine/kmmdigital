<?php

namespace App\Listeners;

use App\Events\RegistrationAlert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendEmailAlert
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RegistrationAlert  $event
     * @return void
     */
    public function handle(RegistrationAlert $event)
    {
        $data = $event->user;
        
        // Mail::send('emails.register', $data, function($message) use ($data) {
            // $message->to($data['email']);
            // $message->subject('Registration Alert Mail');
        // });        
    }
}
