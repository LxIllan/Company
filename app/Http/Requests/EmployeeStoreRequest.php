<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'age' => ['required', 'integer'],
            'salary' => ['required', 'numeric', 'between:-999999.99,999999.99'],
            'email' => ['required', 'email', 'max:100'],
            'address' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:15'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ];
    }
}
