<?php

namespace App\Listeners;

use App\Events\ResetPasswordAlert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendResetPasswordEmailAlert
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
     * @param  ResetPasswordAlert  $event
     * @return void
     */
    public function handle(ResetPasswordAlert $event)
    {   
        $data = $event->obj;
        Mail::send('emails.reset', $data, function($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Reset Password');
        });         
    }
}
