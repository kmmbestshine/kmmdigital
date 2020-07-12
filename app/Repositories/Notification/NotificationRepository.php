<?php

namespace App\Repositories\Notification;

use Auth;
use App\User;
use App\Models\UserNotification;
use Carbon\Carbon;

class NotificationRepository implements NotificationInterface
{
    public $userNotification;

    function __construct(UserNotification $userNotification) {
        $this->userNotification = $userNotification;
    }

    public function getNotifications()
    {   
        $notifications = $this->userNotification::where('module_id',Auth::user()->module_id)->orderBy('created_at','DESC')->paginate(10);
        return $notifications;
    }  

    public function updateNotificationCount($request)
    {   
        $userObj = User::find(Auth::user()->id);
        $userObj->notify_count = 0;
        $userObj->update(); 
        return "count updated successfully";
    }           
}

?>