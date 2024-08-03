<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'id' => 'required|integer|exists:projects,id',
            'name' => 'required|min:5|max:150',
            'department' => 'required|min:2|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:Active,Inactive'
        ];
    }
}
