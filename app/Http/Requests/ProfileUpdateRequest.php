<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],

            // allow user to change password (optional)
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            // user detail fields (optional)
            'gender' => ['nullable', 'in:L,P,O'],
            'address_home' => ['nullable', 'string', 'max:1000'],
            'home_city' => ['nullable', 'string', 'max:255'],
            'address_work' => ['nullable', 'string', 'max:1000'],
            'work_city' => ['nullable', 'string', 'max:255'],
            'type_asesor' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];

        // Allow admin users to update role and is_active via profile
        if ($this->user() && method_exists($this->user(), 'hasAnyRole') && $this->user()->hasAnyRole(['admin', 'adminlanding'])) {
            $rules['role'] = ['required', 'exists:roles,name'];
            $rules['is_active'] = ['required', 'boolean'];
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     * Normalize decimal separators (replace comma with dot) so numeric validation accepts localized input.
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();

        foreach (['latitude', 'longitude'] as $key) {
            if (isset($input[$key]) && is_string($input[$key])) {
                // remove whitespace and replace comma with dot
                $input[$key] = str_replace(',', '.', trim($input[$key]));
                // normalize multiple dots (e.g. "-7,27.072" -> "-7.27.072" -> keep first dot)
                if (substr_count($input[$key], '.') > 1) {
                    $parts = explode('.', $input[$key]);
                    $input[$key] = $parts[0].'.'.implode('', array_slice($parts,1));
                }
            }
        }

        $this->merge($input);
    }
}
