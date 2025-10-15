<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:asesor,internal,umum',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi.',
            'type.required' => 'Silakan pilih tipe kehadiran.',
            'type.in' => 'Tipe yang dipilih tidak valid.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ];
    }

}
