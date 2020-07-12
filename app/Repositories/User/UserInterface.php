<?php 

namespace App\Repositories\User;

interface UserInterface {

    /**
     * Get activated main modules
     *
     */
    public function getActivatedMainModules();
    
    /**
     * Store registration form data
     *
     * @param array $value
     */
    public function store($value);

    /**
     * change current password
     *
     * @param array $value
     */    
    public function changePassword($value);

    /**
     * Get all users
     *
     */
    public function getUsers();

    /**
     * search all users
     *
     * @param array $value
     */
    public function usersSearch($value);      
}

?>