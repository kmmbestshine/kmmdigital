<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainModuleFormRequest extends FormRequest
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
            'main_module_name' => 'required|string'
        ];
    }
}
