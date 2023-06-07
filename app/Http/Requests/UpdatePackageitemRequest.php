<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageitemRequest extends FormRequest
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
            'product_id' => 'required',
            'package_id'=>'required',
            'quantity'=>'required',
            'price'=>'required'
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 400));
    }
}
