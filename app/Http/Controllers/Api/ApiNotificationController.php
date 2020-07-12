<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\User;

class ApiNotificationController extends Controller
{
    // public function getNotificationCount($module_id,$user_id)
    // {
    //     $unseenNotificationCount = 0;
    //     $notifications = UserNotification::where('module_id', $module_id)->get();
    //     foreach ($notifications as $notification) {
    //         $explodeArray = explode(",", $notification->seen_users_id);
    //         if(!in_array($user_id,$explodeArray)){          
    //             $unseenNotificationCount++;
    //         }       
    //     }
    //     return response()->json($unseenNotificationCount);
    // } 

    /**
     * Get notification count for users.
     *
     * @param int $user_id
     * @return int $notifyCount
     */
    public function getNotificationCountFromUserTable($user_id)
    {   
        $userObj = User::where('id', $user_id)->select('notify_count')->first();
        $notifyCount = $userObj->notify_count;
        if(!$notifyCount)
        $notifyCount = 0;
        return response()->json($notifyCount);
    }     

    /**
     * Get notifications.
     *
     * @param int $module_id
     * @param int $user_id
     * @return array $notifications
     */
    public function getNotifications($module_id,$user_id)
    {   
        $userObj = User::find($user_id);
        $userObj->notify_count = 0;
        $userObj->update(); 
        $notifications = UserNotification::where('module_id', $module_id)->get();
        return response()->json($notifications);
    }

    /**
     * Get notification details.
     *
     * @param int $user_id
     * @param int $id
     * @return object $notificationDetails
     */
    public function getNotificationDetails($user_id,$id)
    {      
        $userNotificationObj = UserNotification::find($id);      

        $explodeArray = explode(",", $userNotificationObj->seen_users_id);
        if(!in_array($user_id,$explodeArray)){            
            array_push($explodeArray, $user_id);
        }
        $implodeArray = implode(",", $explodeArray);  
        $userNotificationObj->seen_users_id = $implodeArray;      
        $userNotificationObj->update();      
        return response()->json($notificationDetails);      
    }       
}
