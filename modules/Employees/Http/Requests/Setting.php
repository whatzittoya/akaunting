<?php

namespace Modules\Employees\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Setting extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'default_role_id' => 'required|integer',
            'default_salary_type' => 'required|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
