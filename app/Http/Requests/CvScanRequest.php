<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CvScanRequest extends FormRequest
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
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'job_id' => ['required', 'exists:jobs,id'],
        ];
    }

    /**
     * Get custom messages for validator errors (Bahasa Indonesia).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pdf_file.required' => 'File CV wajib diunggah.',
            'pdf_file.file' => 'CV harus berupa file yang valid.',
            'pdf_file.mimes' => 'CV harus dalam format PDF.',
            'pdf_file.max' => 'Ukuran file CV maksimal 5MB.',
            'job_id.required' => 'Lowongan kerja wajib dipilih.',
            'job_id.exists' => 'Lowongan kerja yang dipilih tidak ditemukan.',
        ];
    }
}
