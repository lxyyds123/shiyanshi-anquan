<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Test extends FormRequest
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
            'a1' => 'required',
            'a2' => 'required',
            'a3' => 'required',
            'a4' => 'required',
            'a5' => 'required',
            'a6' => 'required',
            'a7' => 'required',
            'a8' => 'required',
            'a9' => 'required',
            'a10' => 'required',
            'a11' => 'required',
            'a12' => 'required',
            'a13' => 'required',
            'a14' => 'required',
            'a15' => 'required',
            'a16' => 'required',
            'a17' => 'required',
            'a18' => 'required',
            'a19' => 'required',
            'a20' => 'required',
            'b1' => 'required',
            'b2' => 'required',
            'b3' => 'required',
            'b4' => 'required',
            'b5' => 'required',
            'b6' => 'required',
            'b7' => 'required',
            'b8' => 'required',
            'b9' => 'required',
            'b10' => 'required',
            'b11' => 'required',
            'b12' => 'required',
            'b13' => 'required',
            'b14' => 'required',
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'c4' => 'required',
            'c5' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator){

        throw(new HttpResponseException(json_fail('参数错误',$validator->errors()->all(),422)));
    }
}
