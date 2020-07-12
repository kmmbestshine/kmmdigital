<?php 

namespace App\Repositories\Exam;

interface ExamInterface {

    /**
     * Get exams.
     *
     */    
    public function getExams();

    /**
     * Get activated main modules .
     *
     */ 
    public function getActivatedMainModules();

    /**
     * Ajax sub modules by main modules.
     *
     * @param array $value
     */     
    public function ajaxSubModulesByMainModules($value);

    /**
     * Get main modules by id.
     *
     * @param int $sub_module_id
     */ 
    public function getSubModuleByID($sub_module_id);

    /**
     * Store exam form data.
     *
     * @param array $value
     */
    public function store($value);

    /**
     * Edit exam record.
     *
     * @param string $slug
     */
    public function edit($slug);

    /**
     * Update exam record.
     *
     * @param array $value
     * @param string $slug
     */
    public function update($value,$slug);

    /**
     * Delete exam record.
     *
     * @param string $slug
     */
    public function delete($slug);
}

?>