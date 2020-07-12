<?php

namespace App\Repositories\Report;

use App\Repositories\Report\ReportInterface as ReportInterface;
use Auth;
use App\Models\ReportError;
use Carbon\Carbon;

class ReportRepository implements ReportInterface
{
    public $reportError;

    function __construct(ReportError $reportError) {
        $this->reportError = $reportError;
    }

    public function getReportErrors()
    {   
        $reports = $this->reportError::latest('created_at')->paginate(5);
        return $reports;
    }       
    
    public function sendReportError($value)
    {  	
    	$this->reportError->name = $value["name"];
    	$this->reportError->email = $value["email"];
        // $this->reportError->screen_shot_image = $value["image"];
    	$this->reportError->content = $value["report"];
    	$this->reportError->save();
        return "success";
    } 

    public function sendReportToDevelopmentTeam($id)
    {   
        // send sms and email to development team
        return "Report has been forwarded to development team";
    }          
}

?>