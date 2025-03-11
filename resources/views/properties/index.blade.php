@extends('layouts.app')

@section('title', 'Propriedades')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Propriedades</h1>
        <a href="{{ route('properties.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Nova Propriedade
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                <input type="text" name="city" value="{{ request('city') }}" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <input type="text" name="state" value="{{ request('state') }}" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300">
                    <option value="">Todos</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponível</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Ocupado</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Propriedades -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Código
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Proprietário
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Localização
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Diária
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
                @forelse($properties as $property)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $property->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $property->accommodation_code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $property->owner->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $property->city }}/{{ $property->state }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            R$ {{ number_format($property->daily_rate, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $property->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $property->status === 'available' ? 'Disponível' : 'Ocupado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('properties.show', $property) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Ver
                            </a>
                            <a href="{{ route('properties.edit', $property) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                Editar
                            </a>
                            <form action="{{ route('properties.destroy', $property) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Tem certeza que deseja excluir esta propriedade?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma propriedade encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
