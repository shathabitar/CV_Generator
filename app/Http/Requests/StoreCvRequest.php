<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            
            // Education
            'education' => 'nullable|array',
            'education.*.degree' => 'required|string|max:255',
            'education.*.institution' => 'required|string|max:255',
            'education.*.year' => 'required|string|max:50',
            
            // Experience
            'experience' => 'nullable|array',
            'experience.*.position' => 'required|string|max:255',
            'experience.*.company' => 'required|string|max:255',
            'experience.*.start_date' => 'required|string|max:50',
            'experience.*.end_date' => 'nullable|string|max:50',
            'experience.*.description' => 'nullable|string',
            
            // References
            'reference' => 'nullable|array',
            'reference.*.name' => 'required|string|max:255',
            'reference.*.company' => 'required|string|max:255',
            'reference.*.phone_number' => 'required|string|max:50',
            'reference.*.email' => 'required|email|max:255',
            
            // Certificates
            'certificate' => 'nullable|array',
            'certificate.*.title' => 'required|string|max:255',
            'certificate.*.company' => 'required|string|max:255',
            'certificate.*.date' => 'required|string|max:50',
            
            // Skills
            'technical_skills' => 'nullable|array',
            'technical_skills.*' => 'string',
            'custom_technical_skills' => 'nullable|array',
            'custom_technical_skills.*' => 'string',
            'soft_skills' => 'nullable|array',
            'soft_skills.*' => 'string',
            'custom_soft_skills' => 'nullable|array',
            'custom_soft_skills.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'about.required' => 'About section is required',
            'photo.image' => 'Photo must be an image file',
            'photo.max' => 'Photo size cannot exceed 2MB',
        ];
    }
}