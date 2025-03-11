<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guest_id' => ['required', 'exists:guests,id'],
            'property_id' => ['required', 'exists:properties,id'],
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if ($this->isPropertyOccupied($value)) {
                        $fail('A propriedade já está reservada para esta data.');
                    }
                },
            ],
            'check_out' => [
                'required',
                'date',
                'after:check_in',
                function ($attribute, $value, $fail) {
                    if ($this->isPropertyOccupied($value)) {
                        $fail('A propriedade já está reservada para esta data.');
                    }
                },
            ],
            'cleaning_fee' => ['nullable', 'numeric', 'min:0'],
            'status' => [
                'required',
                Rule::in(['pending', 'confirmed', 'cancelled', 'completed']),
            ],
        ];
    }

    protected function isPropertyOccupied($date): bool
    {
        $propertyId = $this->input('property_id');
        $reservationId = $this->route('reservation'); // Para casos de update

        return \App\Models\Reservation::query()
            ->where('property_id', $propertyId)
            ->where('id', '!=', $reservationId)
            ->where('status', 'confirmed')
            ->where(function ($query) use ($date) {
                $query->where('check_in', '<=', $date)
                      ->where('check_out', '>=', $date);
            })
            ->exists();
    }

    public function messages(): array
    {
        return [
            'guest_id.required' => 'O hóspede é obrigatório.',
            'guest_id.exists' => 'Hóspede inválido.',
            'property_id.required' => 'A propriedade é obrigatória.',
            'property_id.exists' => 'Propriedade inválida.',
            'check_in.required' => 'A data de check-in é obrigatória.',
            'check_in.date' => 'Data de check-in inválida.',
            'check_in.after_or_equal' => 'A data de check-in deve ser hoje ou uma data futura.',
            'check_out.required' => 'A data de check-out é obrigatória.',
            'check_out.date' => 'Data de check-out inválida.',
            'check_out.after' => 'A data de check-out deve ser posterior ao check-in.',
            'cleaning_fee.numeric' => 'A taxa de limpeza deve ser um valor numérico.',
            'cleaning_fee.min' => 'A taxa de limpeza não pode ser negativa.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Status inválido.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has(['check_in', 'check_out'])) {
            $this->merge([
                'check_in' => Carbon::parse($this->check_in)->startOfDay(),
                'check_out' => Carbon::parse($this->check_out)->endOfDay(),
            ]);
        }
    }
}
