@extends('layouts.app')

@section('title', 'Proprietários')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Proprietários</h1>
        <a href="{{ route('owners.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Novo Proprietário
        </a>
    </div>

    <!-- Lista de Proprietários -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        CPF/CNPJ
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cidade/Estado
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Propriedades
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($owners as $owner)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $owner->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $owner->person_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $owner->document_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $owner->city }}/{{ $owner->state }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $owner->properties_count ?? $owner->properties->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('owners.show', $owner) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Ver
                            </a>
                            <a href="{{ route('owners.edit', $owner) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                Editar
                            </a>
                            <form action="{{ route('owners.destroy', $owner) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Tem certeza que deseja excluir este proprietário?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum proprietário cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
