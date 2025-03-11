@extends('layouts.app')

@section('title', 'Hóspedes')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Hóspedes</h1>
        <a href="{{ route('guests.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Novo Hóspede
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('guests.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por nome, CPF, email ou telefone"
                    class="w-full rounded-md border-gray-300">
            </div>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Buscar
            </button>
        </form>
    </div>

    <!-- Lista de Hóspedes -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        CPF
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Telefone
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cidade/Estado
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($guests as $guest)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $guest->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $guest->cpf }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $guest->phone }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $guest->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $guest->city }}/{{ $guest->state }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('guests.show', $guest) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Ver
                            </a>
                            <a href="{{ route('guests.edit', $guest) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                Editar
                            </a>
                            <form action="{{ route('guests.destroy', $guest) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Tem certeza que deseja excluir este hóspede?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum hóspede cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
