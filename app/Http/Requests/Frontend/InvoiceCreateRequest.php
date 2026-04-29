<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCreateRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required',
            'city_or_town' => 'nullable|string',
            'p_address' => 'required|string',
            'zip_code' => 'nullable',
            'address' => 'nullable|string',
            'total_amount' => 'required',
            'orderProducts' => 'required',
            'payment_method' => 'required'
        ];
    }
    public function messages(): array
    {
        return [
            'total_amount.required' => 'Somethings went wrong!',
            'payment_method.required' => 'Please select a payment method.'
        ];
    }
}
