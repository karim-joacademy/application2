<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => 'required|in:I,B,i,b',
            'email' => 'required|email|unique:customers',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postalCode' => 'required',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'postal_code' => $this->postalCode
        ]);
    }
}
