<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|exists:users,email',
            'phone_number' => 'required',
            'avatar' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:20480',
            's_f_name' => 'nullable|string',
            's_phone' => 'nullable',
            's_city_or_town' => 'nullable',
            's_p_address' => 'nullable',
            'zip_code' => 'nullable',
            's_address' => 'nullable',
        ];
    }
    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The Username field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'The provided email does not exist in our records.',
            'phone_number.required' => 'The phone field is required.',
            'avatar.mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif, or svg.',
            'avatar.max' => 'The avatar size may not exceed 20MB.',
        ];
    }
}
