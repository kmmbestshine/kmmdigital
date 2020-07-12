<?php

namespace App\Listeners;

use App\Events\NewExamNotificationAlert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Models\Exam;
use App\Models\SubModule;
use App\Models\UserNotification;

class SendNotificationForNewExamAlert
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
     * @param  NewExamNotificationAlert  $event
     * @return void
     */
    public function handle(NewExamNotificationAlert $event)
    {   
        $exam_id = $event->params;
        $examObj = Exam::find($exam_id);
        $subModuleObj = SubModule::find($examObj->sub_module_id);    
        $title = $examObj->name;
        $url = "/dashboard/".$subModuleObj->slug."/questions/view/".$examObj->slug;
        $userNotificationObj = new UserNotification();
        $userNotificationObj->module_id = $subModuleObj->main_module_id;
        $userNotificationObj->sub_module_id = $subModuleObj->id;
        $userNotificationObj->exam_id = $exam_id;
        $userNotificationObj->title = $title;
        $userNotificationObj->url = $url;
        $userNotificationObj->save();

        //TODO:added to queue list later
        $users = User::where('module_id',$subModuleObj->main_module_id)->get(); 
        foreach ($users as $key => $user) {
            $user->notify_count = $user->notify_count+1;
            $user->update();
        }            
    }
}
