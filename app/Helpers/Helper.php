<?php 

use App\Models\Exam;
use App\Models\Mark;
use App\Models\SubscribePlanMaster;
use App\Models\Question;
use App\Models\SubModule;
use App\User;
use App\Models\UserNotification;

/**
 * Get exams.
 *     
 * @return array $exams
 */
function getExams()
{   
    $exams = Exam::where('status',1)->get();
    return $exams;
} 

/**
 * Count current user exam taken.
 *     
 * @param int $exam_id
 * @return int $count
 */
function currentUserExamTakenCount($exam_id)
{   
	$count = Mark::where('exam_id',$exam_id)->where('user_id',Auth::user()->id)->count();
	return $count;
} 

/**
 * Count question.
 *     
 * @param int $exam_id
 * @return int $count
 */
function getQuestionCount($exam_id)
{   
	$count = Question::where('exam_id',$exam_id)->count();
	return $count;
} 

/**
 * Current user exam taken mark.
 *     
 * @param int $exam_id
 * @return object $mark
 */
function currentUserExamTakenMark($exam_id)
{   
	$mark = Mark::where('exam_id',$exam_id)->where('user_id',Auth::user()->id)->first();
	return $mark;
} 

/**
 * Validate exam date with current date.
 *     
 * @param date $exam_date
 * @return string $result
 */
function validateExamDateWithCurrentDate($exam_date)
{   
	$expire = strtotime($exam_date);
	$today = strtotime("today midnight");

	if($today > $expire){
	    $result = "expired";
	} else {
	    $result = "active";
	}
	return $result;
} 

/**
 * Check subscribe offer available.
 *     
 * @param int $subscribe_id
 * @return object $subscribePlanObj | boolean
 */
function checkOfferAvailable($subscribe_id)
{   
	$subscribePlanObj = SubscribePlanMaster::find($subscribe_id);
	if($subscribePlanObj->offer_applicable == 1){
		$today_date = date('d-m-Y');		
		$start_date = $subscribePlanObj->offer_start_date;
		$end_date = $subscribePlanObj->offer_end_date;
		// Convert to timestamp
		$start_ts = strtotime($start_date);
		$end_ts = strtotime($end_date);
		$today_ts = strtotime($today_date);

		// Check that user date is between start & end
		if(($today_ts >= $start_ts) && ($today_ts <= $end_ts)){
			return $subscribePlanObj->offer_percentage;
		}else{
			return false;
		}
	}else{
		return false;
	}
} 

/**
 * Get activated sub modules.
 *     
 * @param int $main_module_id
 * @return array $subModules
 */
function getActivatedSubModules($main_module_id)
{   
    $subModules = SubModule::where('main_module_id',$main_module_id)->limit(4)->get();
    return $subModules;
} 

/**
 * Get notification count for current user.
 *     
 * @return int $notifyCount
 */
function getNotificationCount()
{   
    // $unseenNotificationCount = 0;
    // $notifications = UserNotification::where('module_id',Auth::user()->module_id)->get();
    // foreach ($notifications as $notification) {
    //     $explodeArray = explode(",", $notification->seen_users_id);
    //     if(!in_array(Auth::user()->id,$explodeArray)){          
    //         $unseenNotificationCount++;
    //     }       
    // }
    // return $unseenNotificationCount;

    $userObj = User::where('id', Auth::user()->id)->select('notify_count')->first();
    $notifyCount = $userObj->notify_count;
    if(!$notifyCount)
    $notifyCount = 0;
    return $notifyCount;    
} 

/**
 * Get notifications for current user.
 *     
 * @return array $notifications
 */
function getNotifications()
{   
    $notifications = UserNotification::where('module_id',Auth::user()->module_id)->orderBy('created_at','DESC')->limit(5)->get();
    return $notifications;
}   
