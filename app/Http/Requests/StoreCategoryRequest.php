<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCategoryRequest extends FormRequest
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
            'name' => ['required'],
<<<<<<< HEAD
            'image' => 'nullable|mimes:jpeg,png,jpg'
=======
            'image' => 'nullable|mimes:jpeg,png'
>>>>>>> 6c57b7057b6b0c5eb144fdf3874a5d1af9853e90
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The name cannot start or be a number.',
        ];
    }

    public function attributes() : array
    {
        return [
            'name' => 'Category name',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
