<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow administrators to manage certifications by default.
        // You can adjust this to use policies or roles as needed.
        return $this->user() && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'issued_at' => 'nullable|date',
            'year' => 'nullable|integer|min:1900|max:2100',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ];
    }
}

