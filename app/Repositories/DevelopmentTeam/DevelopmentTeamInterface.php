<?php 

namespace App\Repositories\DevelopmentTeam;

interface DevelopmentTeamInterface {

    public function getDevelopmentTeams();

    public function save($value);       

    public function edit($slug); 

    public function update($value,$slug); 

    public function activate($slug); 

    public function deactivate($slug);           

    public function delete($slug);           
}

?>