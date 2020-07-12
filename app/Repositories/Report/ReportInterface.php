<?php 

namespace App\Repositories\Report;

interface ReportInterface {

    public function getReportErrors();   

    public function sendReportError($value);   

    public function sendReportToDevelopmentTeam($id);     
}

?>