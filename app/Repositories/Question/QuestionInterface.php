<?php 

namespace App\Repositories\Question;

interface QuestionInterface {

    public function getExams();

    public function getActivatedSubModules();

    public function getSampleQuestionPapers();

    public function postSampleQuestion($request);

    public function store($value);

    public function viewQuestions($exam_slug);
    
    public function viewQuestionsForUser($request,$exam_slug);
    
    public function totalQuestions($exam_slug);
    
    public function getSubjectTitles($exam_slug);

    public function getSingleExam($exam_slug);

    public function getMark($exam_slug);

    public function postAnswers($input,$exam_slug);

}

?>