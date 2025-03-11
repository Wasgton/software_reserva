@extends('layouts.app')

@section('title', 'Relatórios para Proprietários')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Relatórios para Proprietários</h1>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('reports.owners') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proprietário</label>
                <select name="owner_id" class="w-full rounded-md border-gray-300">
                    <option value="">Selecione um proprietário</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>
                            {{ $owner->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                <select name="period" class="w-full rounded-md border-gray-300">
                    <option value="current_month" {{ request('period') == 'current_month' ? 'selected' : '' }}>Mês Atual</option>
                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Mês Anterior</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Personalizado</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Gerar Relatório
                </button>
            </div>
        </form>
    </div>

    @if(isset($report))
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    Relatório: {{ $report['owner']->name }}
                </h2>
                <div class="space-x-2">
                    <a href="{{ route('reports.owners.export', ['owner' => $report['owner']->id, 'format' => 'pdf']) }}"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Exportar PDF
                    </a>
                    <a href="{{ route('reports.owners.export', ['owner' => $report['owner']->id, 'format' => 'excel']) }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Exportar Excel
                    </a>
                </div>
            </div>

            <!-- Resumo por Propriedade -->
            @foreach($report['properties'] as $property)
                <div class="border rounded-lg p-4 mb-4">
                    <h3 class="text-lg font-semibold mb-4">{{ $property['name'] }}</h3>

                    <!-- Reservas -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium mb-2">Reservas do Período</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Período</th>
                                    <th class="px-4 py-2 text-left">Hóspede</th>
                                    <th class="px-4 py-2 text-left">Diárias</th>
                                    <th class="px-4 py-2 text-left">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($property['reservations'] as $reservation)
                                    <tr>
                                        <td class="px-4 py-2">
                                            {{ $reservation->check_in->format('d/m/Y') }} -
                                            {{ $reservation->check_out->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2">{{ $reservation->guest->name }}</td>
                                        <td class="px-4 py-2">{{ $reservation->nights }}</td>
                                        <td class="px-4 py-2">
                                            R$ {{ number_format($reservation->total_amount, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                            Nenhuma reserva no período.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-md font-medium mb-2">Receitas</h4>
                            <ul class="space-y-2">
                                <li class="flex justify-between">
                                    <span>Reservas:</span>
                                    <span class="text-green-600">
                                        R$ {{ number_format($property['revenue']['reservations'], 2, ',', '.') }}
                                    </span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Outros:</span>
                                    <span class="text-green-600">
                                        R$ {{ number_format($property['revenue']['other'], 2, ',', '.') }}
                                    </span>
                                </li>
                                <li class="flex justify-between font-semibold">
                                    <span>Total Receitas:</span>
                                    <span class="text-green-600">
                                        R$ {{ number_format($property['revenue']['total'], 2, ',', '.') }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-md font-medium mb-2">Despesas</h4>
                            <ul class="space-y-2">
                                <li class="flex justify-between">
                                    <span>Limpeza:</span>
                                    <span class="text-red-600">
                                        R$ {{ number_format($property['expenses']['cleaning'], 2, ',', '.') }}
                                    </span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Manutenção:</span>
                                    <span class="text-red-600">
                                        R$ {{ number_format($property['expenses']['maintenance'], 2, ',', '.') }}
                                    </span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Utilidades:</span>
                                    <span class="text-red-600">
                                        R$ {{ number_format($property['expenses']['utilities'], 2, ',', '.') }}
                                    </span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Comissão Maruwa:</span>
                                    <span class="text-red-600">
                                        R$ {{ number_format($property['expenses']['commission'], 2, ',', '.') }}
                                    </span>
                                </li>
                                <li class="flex justify-between font-semibold">
                                    <span>Total Despesas:</span>
                                    <span class="text-red-600">
                                        R$ {{ number_format($property['expenses']['total'], 2, ',', '.') }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Resultado Final -->
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">Valor a Receber:</span>
                            <span class="text-2xl font-bold {{ $property['net_amount'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                R$ {{ number_format($property['net_amount'], 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Totais Gerais -->
            <div class="mt-6 pt-6 border-t">
                <h3 class="text-lg font-semibold mb-4">Resumo Geral</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-600">Total Receitas</h4>
                        <p class="text-2xl font-bold text-green-600">
                            R$ {{ number_format($report['totals']['revenue'], 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-600">Total Despesas</h4>
                        <p class="text-2xl font-bold text-red-600">
                            R$ {{ number_format($report['totals']['expenses'], 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-600">Total a Receber</h4>
                        <p class="text-2xl font-bold {{ $report['totals']['net_amount'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($report['totals']['net_amount'], 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
