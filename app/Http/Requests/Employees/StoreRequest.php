<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'names' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:employees,email'],
            'telephone' => [
                'required',
                'regex:/^2507\d{8}$/',
                'unique:employees,telephone',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'telephone.regex' => 'Telephone must be a 12-digit number starting with 2507.',
        ];
    }
}
