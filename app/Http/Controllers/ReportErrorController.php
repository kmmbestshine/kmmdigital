<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Report\ReportInterface as ReportInterface;

class ReportErrorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
        $this->middleware('admin', ['only' => ['index']]);
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $reports = $this->report->getReportErrors();
        if ($request->ajax()) {
            return view('report.load', ['reports' => $reports])->render();  
        }        
        return view('report.index',compact('reports'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendReportError(Request $request)
    {   
    	$value = $request->all();
    	$data = $this->report->sendReportError($value);
        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendReportToDevelopmentTeam($id)
    {   
        $data = $this->report->sendReportToDevelopmentTeam($id);
        return redirect()->route('getReportErrors')->with('sendReportToDevelopmentTeamSuccess', $data);
    }             
}
