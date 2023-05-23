<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
        return [
            'invoiceno' => 'unique:details,invoiceno',
        ];
    }

    public function messages(){
        return [
            'invoiceno.unique'=>'The item code has already been added. (# '. $this->invoiceno .')'
        ];
    }
}
