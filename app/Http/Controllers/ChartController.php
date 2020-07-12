<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Chart\ChartInterface as ChartInterface;

class ChartController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ChartInterface $chart)
    {
        $this->chart = $chart;
        $this->middleware('user', ['only' => ['index']]);
    }   

    /**
     * Show the chart page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
		$chart = $this->chart->prepareChart();
        return view('chart.chart',compact('chart'));
    }    
}
