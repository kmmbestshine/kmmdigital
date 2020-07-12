<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'state' => 'required',
            'board_type' => 'required',
            'medium' => 'required',
            'module_type' => 'required',
            'sub_module_type' => 'required',
            'exam_name' => 'required',  
            'exam_duration' => 'required',
            'exam_date' => 'required'            
        ];
    }
}
