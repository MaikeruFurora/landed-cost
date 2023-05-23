<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $passwordRule = (empty($this->id))?'required':'sometimes';
        
        return [
             
            'name'=>'required',

            'email'=>'required|unique:users,email,'.$this->id,

            'username'=>'required|unique:users,username,'.$this->id,

            'password'=>[$passwordRule,'confirmed']

        ];
    }

    protected function prepareForValidation()
    {
        if(empty($this->password)) {
            $this->request->remove('password');
        }
    }
}