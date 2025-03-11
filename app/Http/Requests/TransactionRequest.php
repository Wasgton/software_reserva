<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'reservation_id' => 'nullable|exists:reservations,id',
            'type' => 'required|in:income,expense',
            'category' => 'required|in:reservation,cleaning,maintenance,utility,commission,deposit,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date'
        ];
    }

    public function messages(): array
    {
        return [
            'property_id.required' => 'A propriedade é obrigatória',
            'property_id.exists' => 'Propriedade inválida',
            'reservation_id.exists' => 'Reserva inválida',
            'type.required' => 'O tipo é obrigatório',
            'type.in' => 'Tipo inválido',
            'category.required' => 'A categoria é obrigatória',
            'category.in' => 'Categoria inválida',
            'description.required' => 'A descrição é obrigatória',
            'amount.required' => 'O valor é obrigatório',
            'amount.numeric' => 'O valor deve ser numérico',
            'amount.min' => 'O valor deve ser maior que zero',
            'transaction_date.required' => 'A data é obrigatória',
            'transaction_date.date' => 'Data inválida'
        ];
    }
}
