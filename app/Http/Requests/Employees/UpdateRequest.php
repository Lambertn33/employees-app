<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'names' => ['sometimes', 'required', 'string', 'max:255'],

            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                "unique:employees,email,{$employeeId}",
            ],

            // if you allow updating code; otherwise remove this block
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                "unique:employees,code,{$employeeId}",
            ],

            'telephone' => [
                'sometimes',
                'required',
                'regex:/^2507\d{8}$/',
                "unique:employees,telephone,{$employeeId}",
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
