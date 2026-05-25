<?php

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class JobIngestRequest extends FormRequest
{
    /**
     * API endpoints are always authorized at the middleware level.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'source' => 'required|string',
            'scraped_at' => 'required|date',
            'jobs' => 'required|array|min:1',
            'jobs.*.title' => 'required|string|max:255',
            'jobs.*.company' => 'required|string|max:255',
            'jobs.*.location' => 'nullable|string|max:255',
            'jobs.*.employment_type' => ['nullable', 'string', Rule::in(EmploymentType::cases())],
            'jobs.*.salary_min' => 'nullable|integer|min:0',
            'jobs.*.salary_max' => 'nullable|integer|min:0',
            'jobs.*.salary_currency' => 'nullable|string|max:3',
            'jobs.*.description_raw' => 'nullable|string',
            'jobs.*.summary_ai' => 'nullable|string',
            'jobs.*.company_logo' => 'nullable|url|max:2048',
            'jobs.*.tags' => 'nullable|array',
            'jobs.*.tags.*' => 'string',
            'jobs.*.source_url' => 'required|url|max:2048',
            'jobs.*.expires_at' => 'nullable|date',
        ];
    }

    /**
     * Return JSON validation errors instead of redirecting.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
