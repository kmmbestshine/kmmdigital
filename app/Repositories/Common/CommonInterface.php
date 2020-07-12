<?php 

namespace App\Repositories\Common;

interface CommonInterface {

    /**
     * Get all states
     *
     */
    public function getAllStates();

    /**
     * Get all boards
     *
     */
    public function getAllBoards();     

    /**
     * Get all mediums
     *
     */
    public function getAllMediums();

    /**
     * Get state by id
     *
     * @param int $id
     */
    public function getStateByID($id);

    /**
     * Get board by id
     *
     * @param int $id
     */
    public function getBoardByID($id);

    /**
     * Get medium by id
     *
     * @param int $id
     */
    public function getMediumByID($id);
       
}

?>