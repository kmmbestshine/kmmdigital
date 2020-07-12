<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DevelopmentTeam\DevelopmentTeamInterface as DevelopmentTeamInterface;

class DevelopmentTeamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DevelopmentTeamInterface $developmentTeam)
    {	
    	$this->developmentTeam = $developmentTeam;
        $this->middleware('admin', ['only' => ['index']]);
    } 

    /**
     * Display development team page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
    	$teams = $this->developmentTeam->getDevelopmentTeams();     
        return view('team.index',compact('teams'));
    }

    /**
     * Show development team form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
        return view('team.create');
    }

    /**
     * Store development team form data.
     *
     * @param Request $request
     * @return Redirect
     */
    public function post(Request $request)
    {   
    	$value = $request->all();
    	$data = $this->developmentTeam->save($value);
    	return redirect()->route('getDevelopmentTeams')->with('postDeveloperSuccess', $data);
    }  

    /**
     * Edit development team record.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {   
    	$edit = $this->developmentTeam->edit($slug);
    	return view('team.edit',compact('edit'));
    }  

    /**
     * Update development team record.
     *
     * @param Request $request
     * @param string $slug
     * @return Redirect
     */
    public function update(Request $request,$slug)
    {   
    	$value = $request->all();
    	$data = $this->developmentTeam->update($value,$slug);
    	return redirect()->route('getDevelopmentTeams')->with('updateDeveloperSuccess', $data);
    }

    /**
     * Activate development team record.
     *
     * @param string $slug
     * @return Redirect
     */
    public function activate($slug)
    {   
    	$data = $this->developmentTeam->activate($slug);
    	return redirect()->route('getDevelopmentTeams')->with('activateDeveloperSuccess', $data);
    }

    /**
     * Deactivate development team record.
     *
     * @param string $slug
     * @return Redirect
     */
    public function deactivate($slug)
    {   
    	$data = $this->developmentTeam->deactivate($slug);
    	return redirect()->route('getDevelopmentTeams')->with('deactivateDeveloperSuccess', $data);
    } 

    /**
     * Delete development team record.
     *
     * @param string $slug
     * @return Redirect
     */
    public function delete($slug)
    {   
    	$data = $this->developmentTeam->delete($slug);
    	return redirect()->route('getDevelopmentTeams')->with('deleteDeveloperSuccess', $data);
    }                            
}
