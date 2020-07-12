<?php

namespace App\Listeners;

use App\Events\OnlineTestResultAlert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Auth;
use App\Models\Exam;
use App\Models\SubModule;

class SendOnlineTestResultToUserEmail
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
     * @param  OnlineTestResultAlert  $event
     * @return void
     */
    public function handle(OnlineTestResultAlert $event)
    {
        $param = $event->mark;
        $exam = Exam::find($param->exam_id);
        $subModule = SubModule::find($exam->sub_module_id);
        $data = [];
        $data['name'] = Auth::user()->name;
        $data['mark'] = $param->mark;
        $data['negative_mark'] = $param->negative_mark;
        $data['remarks'] = $param->remarks;
        $data['attended_date'] = date('d-m-Y');
        // Mail::send('emails.exam-result', $data, function($message) use ($subModule,$data) {
            // $message->to(Auth::user()->email);
            // $message->subject("Exam Results for ".$subModule->name);
        // });        
    }
}
