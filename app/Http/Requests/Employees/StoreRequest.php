<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Employee;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Employee::class);
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
}
