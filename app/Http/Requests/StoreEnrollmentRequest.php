<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnrollmentRequest extends FormRequest
{
    protected $errorBag = 'addEnrollment';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Student must be fill identity number first
        return !is_null(request()->user()->identity_number);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'practicum_id' => [
                'required',
                'exists:practicums,id',
                // Ensure the combination of user_id and practicum_id is unique
                Rule::unique('enrollments')->where('user_id', $this->user()->id)
            ],
            'study_plan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'transcript' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'practicum_id.unique' => 'You are already enrolled in this practicum.',
        ];
    }
}
