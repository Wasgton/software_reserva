<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OwnerReportExport implements FromArray, WithHeadings, WithMapping, WithStyles
{
    protected $report;

    public function __construct(array $report)
    {
        $this->report = $report;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->report['properties'] as $property) {
            // Dados da propriedade
            $rows[] = [
                'property' => $property['name'],
                'type' => 'Receitas',
                'category' => 'Reservas',
                'amount' => $property['revenue']['reservations']
            ];

            $rows[] = [
                'property' => $property['name'],
                'type' => 'Receitas',
                'category' => 'Outras',
                'amount' => $property['revenue']['other']
            ];

            // Despesas
            foreach ($property['expenses'] as $category => $amount) {
                if ($category !== 'total') {
                    $rows[] = [
                        'property' => $property['name'],
                        'type' => 'Despesas',
                        'category' => ucfirst($category),
                        'amount' => $amount
                    ];
                }
            }

            // Resultado
            $rows[] = [
                'property' => $property['name'],
                'type' => 'Resultado',
                'category' => 'Valor Líquido',
                'amount' => $property['net_amount']
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Propriedade',
            'Tipo',
            'Categoria',
            'Valor'
        ];
    }

    public function map($row): array
    {
        return [
            $row['property'],
            $row['type'],
            $row['category'],
            number_format($row['amount'], 2, ',', '.')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
