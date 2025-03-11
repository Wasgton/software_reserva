@extends('layouts.app')

@section('title', 'Gestão Financeira')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Gestão Financeira</h1>
        <button onclick="document.getElementById('newTransactionModal').classList.remove('hidden')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Nova Transação
        </button>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Receitas do Mês</h3>
            <p class="text-3xl font-bold text-green-600">
                R$ {{ number_format($monthly_revenue, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Despesas do Mês</h3>
            <p class="text-3xl font-bold text-red-600">
                R$ {{ number_format($monthly_expenses, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Saldo do Mês</h3>
            <p class="text-3xl font-bold {{ ($monthly_revenue - $monthly_expenses) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                R$ {{ number_format($monthly_revenue - $monthly_expenses, 2, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('financial.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="type" class="w-full rounded-md border-gray-300">
                    <option value="">Todos</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Receitas</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Despesas</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Transações -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Data
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Descrição
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoria
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Propriedade
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Valor
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $transaction->transaction_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $transaction->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $transaction->formatted_category }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $transaction->property->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $transaction->formatted_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="{{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->formatted_amount }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" onclick="editTransaction({{ $transaction->id }})" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                Editar
                            </a>
                            <form action="{{ route('financial.transactions.destroy', $transaction) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Tem certeza que deseja excluir esta transação?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma transação encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Nova Transação -->
    <div id="newTransactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Nova Transação</h3>
                <button onclick="document.getElementById('newTransactionModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>

            <form action="{{ route('financial.transactions.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <input type="text" name="description" required
                            class="w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Propriedade</label>
                        <select name="property_id" required class="w-full rounded-md border-gray-300">
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select name="type" required class="w-full rounded-md border-gray-300">
                            <option value="income">Receita</option>
                            <option value="expense">Despesa</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <select name="category" required class="w-full rounded-md border-gray-300">
                            <option value="reservation">Reserva</option>
                            <option value="cleaning">Limpeza</option>
                            <option value="maintenance">Manutenção</option>
                            <option value="utility">Utilidade</option>
                            <option value="commission">Comissão</option>
                            <option value="deposit">Caução</option>
                            <option value="other">Outros</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Valor</label>
                        <input type="number" step="0.01" name="amount" required
                            class="w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data</label>
                        <input type="date" name="transaction_date" required
                            class="w-full rounded-md border-gray-300">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Salvar Transação
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
