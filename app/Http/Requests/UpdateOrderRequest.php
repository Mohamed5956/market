<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateOrderRequest extends FormRequest
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
            'firstName' => 'string|max:255',
            'lastName' => 'string|max:255',
            'email' => 'string|email|max:255',
            'user_id' => '',
            'phone' => '',
            'address' => 'string',
            'status' => '',
            'total_price' => '',
            'tracking_no' => '',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' =>false,
            'message' =>"validation errors",
            'data' =>$validator->errors()
        ],
        400
    ));

    }
}
