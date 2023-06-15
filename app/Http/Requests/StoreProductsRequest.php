<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'regex:/^[^0-9][a-zA-Z0-9]*$/'],
            'description'  => ['nullable','regex:/^[^0-9][a-zA-Z0-9]*$/'],
            'price'  => 'required | integer',
            'quantity'  => 'required | integer',
            'trend' => 'required',
            'subcategory_id' => 'required | integer',
            'image' => 'nullable|mimes:jpeg,png'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
