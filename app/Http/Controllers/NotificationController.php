<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Notification\NotificationInterface as NotificationInterface;

class NotificationController extends Controller
{
   	
   	protected $notification;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Show the all notification page.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications(Request $request)
    {   
        $notifications = $this->notification->getNotifications();
        if ($request->ajax()) {
            return view('notification.load', ['notifications' => $notifications])->render();  
        }        
        return view('notification.index',compact('notifications'));    	
    }

   /**
     * Update Notification Count.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateNotificationCount(Request $request)
    {   
        if ($request->ajax()) {
            $data = $this->notification->updateNotificationCount($request);
            return $data;  
        }              
    }    
}
