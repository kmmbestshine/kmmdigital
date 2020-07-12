<?php 

namespace App\Repositories\Notification;

interface NotificationInterface {

    public function getNotifications();

    public function updateNotificationCount($request);
}

?>