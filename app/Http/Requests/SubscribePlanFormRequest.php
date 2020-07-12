<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribePlanFormRequest extends FormRequest
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
        $value = $this->request->all();
        if(isset($value['offer_applicable'])){
            return [
                'module_type' => 'required',
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'time_duration' => 'required|numeric',
                'limit' => 'required|numeric',
                'offer_percentage'=>'required|numeric',
                'offer_start_date'=>'required|date',
                'offer_end_date'=>'required|date|after:offer_start_date'
            ];            
        }else{
            return [
                'module_type' => 'required',
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'time_duration' => 'required|numeric',
                'limit' => 'required|numeric'
            ];            
        }
    }
}
