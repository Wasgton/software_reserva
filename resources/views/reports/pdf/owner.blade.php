<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório do Proprietário</title>
    <style>
        body { font-family: DejaVu Sans; }
        .header { text-align: center; margin-bottom: 30px; }
        .property { margin-bottom: 30px; }
        .property-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .total { font-weight: bold; }
        .positive { color: green; }
        .negative { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Financeiro - {{ $owner->name }}</h1>
        <p>Período: {{ $period['start'] }} a {{ $period['end'] }}</p>
    </div>

    @foreach($properties as $property)
        <div class="property">
            <div class="property-name">{{ $property['name'] }}</div>

            <h3>Receitas</h3>
            <table>
                <tr>
                    <th>Categoria</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Reservas</td>
                    <td>R$ {{ number_format($property['revenue']['reservations'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Outras Receitas</td>
                    <td>R$ {{ number_format($property['revenue']['other'], 2, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total Receitas</td>
                    <td>R$ {{ number_format($property['revenue']['total'], 2, ',', '.') }}</td>
                </tr>
            </table>

            <h3>Despesas</h3>
            <table>
                <tr>
                    <th>Categoria</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Limpeza</td>
                    <td>R$ {{ number_format($property['expenses']['cleaning'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Manutenção</td>
                    <td>R$ {{ number_format($property['expenses']['maintenance'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Utilidades</td>
                    <td>R$ {{ number_format($property['expenses']['utilities'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Comissão</td>
                    <td>R$ {{ number_format($property['expenses']['commission'], 2, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total Despesas</td>
                    <td>R$ {{ number_format($property['expenses']['total'], 2, ',', '.') }}</td>
                </tr>
            </table>

            <h3>Resultado</h3>
            <table>
                <tr class="total">
                    <td>Valor Líquido</td>
                    <td class="{{ $property['net_amount'] >= 0 ? 'positive' : 'negative' }}">
                        R$ {{ number_format($property['net_amount'], 2, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

    <div class="totals">
        <h2>Resumo Geral</h2>
        <table>
            <tr>
                <th>Total Receitas</th>
                <td>R$ {{ number_format($totals['revenue'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Despesas</th>
                <td>R$ {{ number_format($totals['expenses'], 2, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <th>Valor Total a Receber</th>
                <td class="{{ $totals['net_amount'] >= 0 ? 'positive' : 'negative' }}">
                    R$ {{ number_format($totals['net_amount'], 2, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
