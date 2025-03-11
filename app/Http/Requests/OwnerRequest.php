<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('owner');

        return [
            'name' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'person_type' => 'required|in:PF,PJ',
            'nationality' => 'required|string|max:255',
            'document_number' => [
                'required',
                'string',
                'max:20',
                'unique:owners,document_number,' . $id
            ],
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'postal_code' => 'required|string|max:10'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'profession.required' => 'A profissão é obrigatória',
            'person_type.required' => 'O tipo de pessoa é obrigatório',
            'person_type.in' => 'O tipo de pessoa deve ser PF ou PJ',
            'nationality.required' => 'A nacionalidade é obrigatória',
            'document_number.required' => 'O CPF/CNPJ é obrigatório',
            'document_number.unique' => 'Este CPF/CNPJ já está cadastrado',
            'address.required' => 'O endereço é obrigatório',
            'city.required' => 'A cidade é obrigatória',
            'state.required' => 'O estado é obrigatório',
            'state.size' => 'O estado deve ter 2 caracteres',
            'postal_code.required' => 'O CEP é obrigatório'
        ];
    }
}
