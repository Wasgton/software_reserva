@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Reservas</h1>
        <a href="{{ route('reservations.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Nova Reserva
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('reservations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Concluída</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Check-in</label>
                <input type="date" name="check_in" value="{{ request('check_in') }}" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Check-out</label>
                <input type="date" name="check_out" value="{{ request('check_out') }}" class="w-full rounded-md border-gray-300">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Reservas -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Código
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Hóspede
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Propriedade
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Check-in
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Check-out
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Valor Total
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reservations as $reservation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $reservation->reservation_code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $reservation->guest->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $reservation->property->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $reservation->check_in->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $reservation->check_out->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            R$ {{ number_format($reservation->total_amount, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $reservation->status_color === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $reservation->status_color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $reservation->status_color === 'red' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $reservation->status_color === 'blue' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('reservations.show', $reservation) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Ver
                            </a>
                            <a href="{{ route('reservations.edit', $reservation) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                Editar
                            </a>
                            @if($reservation->status === 'pending')
                                <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                        Confirmar
                                    </button>
                                </form>
                            @endif
                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                                <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                        Cancelar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma reserva encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
