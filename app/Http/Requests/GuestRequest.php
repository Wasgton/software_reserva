<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('guest');

        return [
            'name' => 'required|string|max:255',
            'person_type' => 'required|in:PF,PJ',
            'nationality' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'rg' => 'required|string|max:20',
            'cpf' => [
                'required',
                'string',
                'max:14',
                'unique:guests,cpf,' . $id
            ],
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:guests,email,' . $id
            ],
            'birth_date' => 'required|date|before:today',
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
            'person_type.required' => 'O tipo de pessoa é obrigatório',
            'person_type.in' => 'O tipo de pessoa deve ser PF ou PJ',
            'nationality.required' => 'A nacionalidade é obrigatória',
            'profession.required' => 'A profissão é obrigatória',
            'rg.required' => 'O RG é obrigatório',
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'phone.required' => 'O telefone é obrigatório',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Digite um e-mail válido',
            'email.unique' => 'Este e-mail já está cadastrado',
            'birth_date.required' => 'A data de nascimento é obrigatória',
            'birth_date.before' => 'A data de nascimento deve ser anterior a hoje',
            'address.required' => 'O endereço é obrigatório',
            'city.required' => 'A cidade é obrigatória',
            'state.required' => 'O estado é obrigatório',
            'state.size' => 'O estado deve ter 2 caracteres',
            'postal_code.required' => 'O CEP é obrigatório'
        ];
    }
}
