<?php

namespace App\Repositories\DevelopmentTeam;

use App\Repositories\DevelopmentTeam\DevelopmentTeamInterface as DevelopmentTeamInterface;
use Auth;
use App\Models\DevelopmentTeam;
use Carbon\Carbon;

class DevelopmentTeamRepository implements DevelopmentTeamInterface
{
    public $developmentTeam;

    function __construct(DevelopmentTeam $developmentTeam) {
        $this->developmentTeam = $developmentTeam;
    }   
    
    public function getDevelopmentTeams()
    {   
        $developmentTeams = $this->developmentTeam::get();
        return $developmentTeams;
    }  

    public function save($value)
    {   
        $this->developmentTeam->name = $value['name'];
        $this->developmentTeam->code = $value['code'];
        $this->developmentTeam->designation = $value['designation'];
        $this->developmentTeam->status = 1;
        $this->developmentTeam->save();
        return "Developer has been created successfully";
    }

    public function edit($slug)
    {   
    	$edit = $this->developmentTeam::where('slug',$slug)->first();
        return $edit;
    } 

    public function update($value,$slug)
    {   
    	$update = $this->developmentTeam::where('slug',$slug)->first();
        $update->name = $value['name'];
        $update->code = $value['code'];
        $update->designation = $value['designation'];
        $update->update();    	
        return "Developer has been updated successfully";
    } 

    public function activate($slug)
    {   
    	$activate = $this->developmentTeam::where('slug',$slug)->first();
    	$activate->status = 1;
    	$activate->update();
        return "Developer has been activated successfully";
    } 

    public function deactivate($slug)
    {   
    	$deactivate = $this->developmentTeam::where('slug',$slug)->first();
    	$deactivate->status = 0;
    	$deactivate->update();
        return "Developer has been deactivated successfully";
    }  

    public function delete($slug)
    {   
    	$delete = $this->developmentTeam::where('slug',$slug)->first();
    	$delete->delete();
        return "Developer has been deleted successfully";
    }                 
}

?>