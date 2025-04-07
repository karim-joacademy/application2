<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->method() == 'PUT') {
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
        else {
            return [
                'name' => 'sometimes|required',
                'type' => 'sometimes|required|in:I,B,i,b',
                'email' => 'sometimes|required|email|unique:customers',
                'address' => 'sometimes|required',
                'city' => 'sometimes|required',
                'state' => 'sometimes|required',
                'postalCode' => 'sometimes|required',
            ];
        }
    }

    protected function prepareForValidation(): void
    {
        if($this->postalCode) {
            $this->merge([
                'postal_code' => $this->postalCode
            ]);
        }
    }
}
