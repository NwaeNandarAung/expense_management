<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'acc_cat_code'=>'required',
            'name'=>'required',
            'total_price'=>'required',
        ];
    }
    public function messages()
    {
        return[
            'acc_cat_code.required' => 'A category is required for the expense.',
            'name.required' => 'A name is required for the expense.',
            'total_price.required' => 'A total price is required for the expense.',
        ];
    }
}
