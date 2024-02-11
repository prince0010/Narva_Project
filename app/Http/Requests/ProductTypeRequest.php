<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //unique:prod__types,product_name, checks that the product_name is unique in the prod__types table. The third parameter ' . 
            //$this->route('id') is used to ignore the current prod__types's ID when checking for uniqueness. This assumes you're passing the prod__types ID in the route.
           
            'product_name' => 'required|unique:prod__types,product_name,' . $this->route('id'),
        ];
    }
}
