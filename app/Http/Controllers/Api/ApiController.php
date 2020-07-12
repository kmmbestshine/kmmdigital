<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use App\Models\PlanUsageDetail;
use App\Models\MobileAppDetail;
use App\Models\RoleUser;
use App\Models\StateMaster;
use App\Models\BoardMaster;
use App\Models\LanguageMaster;
use App\Models\MainModule;
use Hash;
use JWTAuth;
use Carbon\Carbon;
use Event;
use App\Events\RegistrationAlert;

class ApiController extends Controller
{
    
    /**
     * Get all states.
     *
     * @return array $states
     */
    public function getAllStates()
    {   
        $states = StateMaster::select('id','name')->get();
        return $states;
    }

    /**
     * Get all boards.
     *
     * @return array $boards
     */
    public function getAllBoards()
    {   
        $boards = BoardMaster::select('id','name')->get();
        return $boards;
    }    

    /**
     * Get all mediums.
     *
     * @return array $mediums
     */
    public function getAllMediums()
    {   
        $mediums = LanguageMaster::select('id','name')->get();
        return $mediums;
    }

    /**
     * Get main modules.
     *
     * @return array $mediums
     */
    public function getMainModules($state_id,$board_id,$medium_id)
    {   
        $mainModules = MainModule::select('id','name')->where('state_id',$state_id)->where('board_id',$board_id)->where('medium_id',$medium_id)->get();
        return $mainModules;
    }

    /**
     * Register new user.
     *
     * @param Request $request
     * @return object $userObj
     */
    public function register(Request $request)
    {   
        $value = $request->all();
        $registerSuccess = true;

        // check email
        $emailCount = User::where('email',$value['email'])->count();
        if($emailCount > 0){
            $registerSuccess = false;
            return response()->json(['result'=>'Email already exists']);
        }

        // check phone number
        $phoneNumberCount = User::where('phone_no',$value['phone_no'])->count();
        if($phoneNumberCount > 0){
            $registerSuccess = false;
            return response()->json(['result'=>'Phone no already exists']);
        }

        if($registerSuccess){
            $userObj = new User();
            $userObj->name = $value['name'];
            $userObj->email = $value['email'];
            $userObj->phone_no = $value['phone_no'];
            $userObj->state_id = $value['state_id'];
            $userObj->board_id = $value['board_id'];
            $userObj->module_id = $value['module_id'];
            $userObj->medium_id = $value['medium_id'];
            $userObj->password = bcrypt($value['password']);
            $userObj->save();

            $roleObj = Role::where('name','User')->first();
            $roleUser = new RoleUser();
            $roleUser->role_id = $roleObj->id;
            $roleUser->user_id = $userObj->id;
            $roleUser->save();
            $this->updatePlan($userObj);
            // send email
            $array = [];
            $array['email'] = $value['email'];
            $array['password'] = $value['password'];
            Event::fire(new RegistrationAlert($array));
            return response()->json(['result'=>$userObj]);
        }
    }

    /**
     * Update user plan.
     *
     * @param object $user
     * @return void
     */
    public function updatePlan($user)
    {   
        $plan = new PlanUsageDetail();
        $plan->main_module_id = $user->module_id;
        $plan->plan_name = "Free Trial";
        $plan->user_id = $user->id;
        $plan->plan_limit = 2;
        $plan->days_duration = 5;
        $plan->subscribe_date = Carbon::now();
        $plan->save();
    }     

    /**
     * User login.
     *
     * @param Request $request
     * @return randomstring $token
     */    
    public function login(Request $request)
    {   
        $input = $request->all();
        if (!$token = JWTAuth::attempt($input)) {
            return response()->json(['result' => 'wrong email or password.']);
        }
            return response()->json(['result' => $token]);
    }

    /**
     * Get user details.
     *
     * @param Request $request
     * @return object $user
     */      
    public function getUserDetails(Request $request)
    {
        $input = $request->all();
        $user = JWTAuth::toUser($input['token']);
        // $user = User::with('roles')->find($input['id']);
        return response()->json(['result' => $user]);
    }

    /**
     * Validate token.
     *
     * @return string "validate token"
     */
    public function validateToken()
    {
        return response()->json(['result' => "validate token"]);
    }     

    /**
     * Refresh token.
     *
     * @param Request $request
     * @return randomstring $new_token
     */
    public function refreshToken(Request $request)
    {
        $input = $request->all();
        $new_token = JWTAuth::refresh($input['token']);
        return response()->json(['result' => $new_token]);
    }

    /**
     * Get app version.
     *
     * @return int $version
     */
    public function  getAppVersion(){  
        $version = MobileAppDetail::find(1);
        return response()->json(['result'=>$version->version]);
    }           
}
