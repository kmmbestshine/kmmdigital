<?php

namespace App\Repositories\Common;

use App\Repositories\Common\CommonInterface as CommonInterface;
use App\Models\StateMaster;
use App\Models\BoardMaster;
use App\Models\MediumMaster;

class CommonRepository implements CommonInterface
{   

    /**
     * StateMaster instance
     *
     * @var model instance
     * @access protected
     */    
    protected $state;

    /**
     * BoardMaster instance
     *
     * @var model instance
     * @access protected
     */    
    protected $board;

    /**
     * MediumMaster instance
     *
     * @var model instance
     * @access protected
     */    
    protected $medium;    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct(StateMaster $state, BoardMaster $board, MediumMaster $medium) {
        $this->state = $state;
        $this->board = $board;
        $this->medium = $medium;
    }   
    
    /**
     * Get all states.
     *
     * @return array $states
     */
    public function getAllStates()
    {   
        $states = $this->state::select('id','name')->get();
        return $states;
    }

    /**
     * Get all boards.
     *
     * @return array $boards
     */    
    public function getAllBoards()
    {   
        $boards = $this->board::get();
        return $boards;
    }     

    /**
     * Get all mediums.
     *
     * @return array $mediums
     */
    public function getAllMediums()
    {   
        $mediums = $this->medium::select('id','name')->get();
        return $mediums;
    }

    /**
     * Get state by id
     *
     * @param int $id
     * @return object $state
     */
    public function getStateByID($id){
        $state = $this->state::where('id',$id)->select('id','name')->first();
        return $state;        
    }

    /**
     * Get board by id
     *
     * @param int $id
     * @return object $board
     */
    public function getBoardByID($id){
        $board = $this->board::where('id',$id)->select('id','name')->first();
        return $board;         
    }

    /**
     * Get medium by id
     *
     * @param int $id
     * @return object $medium
     */
    public function getMediumByID($id){
        $medium = $this->medium::where('id',$id)->select('id','name')->first();
        return $medium;         
    }            
}

?>