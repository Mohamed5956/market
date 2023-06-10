<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
//            'product_id' => 'required|exists:products,id',
            'rating' => 'numeric|min:0|max:10',
            'comment' => 'string'
        ];
    }


    public function messages()
    {
        return [
//            'product_id'=>"Product id must be provided",
            'rating'=>"Must be a number between 0 to 10",
            'comment'=>"Must be a text"
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 400));
    }

}
