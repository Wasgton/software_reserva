<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'accommodation_code' => 'required|string|unique:properties,accommodation_code,' . $this->property,
            'owner_id' => 'required|exists:owners,id',
            'address' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'daily_rate' => 'required|numeric|min:0',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:available,occupied'
        ];
    }
}
