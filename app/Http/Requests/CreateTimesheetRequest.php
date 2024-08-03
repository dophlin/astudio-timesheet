<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTimesheetRequest extends FormRequest
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
            'task_name' => 'required|string|min:5|max:150',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.1',
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id'
        ];
    }
}