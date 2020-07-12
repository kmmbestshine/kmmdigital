<?php

namespace App\Repositories\User;

use App\Repositories\User\UserInterface as UserInterface;
use Auth;
use Hash;
use App\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PlanUsageDetail;
use App\Models\MainModule;
use Carbon\Carbon;
use Event;
use App\Events\RegistrationAlert;

class UserRepository implements UserInterface
{
    /**
     * User instance
     *
     * @var model instance
     * @access protected
     */    
    protected $user;

    /**
     * PlanUsageDetail instance
     *
     * @var model instance
     * @access protected
     */    
    protected $plan;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct(User $user, PlanUsageDetail $plan) {
        $this->user = $user;
        $this->plan = $plan;
    }

    /**
     * Get activated main modules.
     *
     * @return array $mainModules
     */
    public function getActivatedMainModules()
    {   
        $mainModules = MainModule::where('status',1)->get();
        return $mainModules;
    } 

    /**
     * Get all users.
     *
     * @return array $users
     */  
    public function getUsers()
    {   
        $users = $this->user::with('plans')->where('id','!=',Auth::user()->id)->get();
        return $users;
    } 

    /**
     * Get all users.
     *
     * @param array $value
     * @return array $users
     */  
    public function usersSearch($value)
    {   
        $from = $value['from_date'];
        $to = $value['to_date'];
        $users = $this->user::with('plans')->where('id','!=',Auth::user()->id)->whereBetween('created_date', array($from, $to))->get();
        return $users;
    }           

    /**
     * store user details.
     *
     * @param array $value
     * @return array $user
     */  
    public function store($value)
    {   
        $this->user->name = $value['name'];
        $this->user->email = $value['email'];
        $this->user->phone_no = $value['phone_no'];
        $this->user->password = bcrypt($value['password']);
        $this->user->ver_name = $value['password'];
        $this->user->state_id = $value['state'];
        $this->user->board_id = $value['board_type'];
        $this->user->medium_id = $value['medium'];
        $this->user->module_id = $value['standard'];
        $this->user->created_date = date("d-m-Y");
        $this->user->save();
        $roleObj = Role::where('name','User')->first();
        $roleUser = new RoleUser();
        $roleUser->role_id = $roleObj->id;
        $roleUser->user_id = $this->user->id;
        $roleUser->save();
        $this->updatePlan($this->user);
        // send email
        $array = [];
        $array['email'] = $value['email'];
        $array['password'] = $value['password'];
        Event::fire(new RegistrationAlert($array));
        return $this->user;
    }   

    /**
     * update user plan.
     *
     * @param array $user
     * @return void
     */  
    public function updatePlan($user)
    {   
        $this->plan->user_id = $user->id;
        $this->plan->main_module_id = $user->module_id;
        $this->plan->plan_name = "Free Trial";
        $this->plan->plan_limit = 2;
        $this->plan->days_duration = 5;
        $this->plan->subscribe_date = Carbon::now();
        $this->plan->save();
    } 

    /**
     * change user current password.
     *
     * @param array $value
     * @return string
     */  
    public function changePassword($value)
    {   
        $current_password = Auth::User()->password;         
        if(Hash::check($value['current_password'], $current_password))
        {          
            $user_id = Auth::User()->id;                       
            $obj_user = User::find($user_id);
            $obj_user->password = Hash::make($value['password']);;
            $obj_user->save(); 
            return "success";
        }else{           
            return "error";   
        }        
    }            
}

?>