<?php

namespace App\Listeners;

use App\Events\ContactUsAlert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendContactUsEmailAlert
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
     * @param  ContactUsAlert  $event
     * @return void
     */
    public function handle(ContactUsAlert $event)
    {
        $value = $event->params;
        $data = [];
        $data['name'] = $value['name'];
        $data['email'] = $value['email'];
        Mail::send('emails.contactus', $data, function($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Contact Us Alert');
        });        
    }
}
