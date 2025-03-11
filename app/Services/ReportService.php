<?php

namespace App\Services;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\OwnerRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF;
use Excel;
use App\Exports\OwnerReportExport;

class ReportService
{
    protected $reservationRepository;
    protected $transactionRepository;
    protected $ownerRepository;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        TransactionRepositoryInterface $transactionRepository,
        OwnerRepositoryInterface $ownerRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->transactionRepository = $transactionRepository;
        $this->ownerRepository = $ownerRepository;
    }

    public function generateOwnerReport($ownerId, Carbon $startDate, Carbon $endDate)
    {
        $owner = $this->ownerRepository->find($ownerId);
        $properties = $owner->properties;

        $propertiesData = [];
        $totals = [
            'revenue' => 0,
            'expenses' => 0,
            'net_amount' => 0
        ];

        foreach ($properties as $property) {
            $reservations = $this->reservationRepository->getByPropertyAndDateRange(
                $property->id,
                $startDate,
                $endDate
            );

            $transactions = $this->transactionRepository->getByPropertyAndDateRange(
                $property->id,
                $startDate,
                $endDate
            );

            $propertyData = [
                'name' => $property->name,
                'reservations' => $reservations,
                'revenue' => [
                    'reservations' => $reservations->sum('total_amount'),
                    'other' => $transactions->where('type', 'income')->sum('amount'),
                    'total' => 0
                ],
                'expenses' => [
                    'cleaning' => $transactions->where('type', 'expense')
                        ->where('category', 'cleaning')
                        ->sum('amount'),
                    'maintenance' => $transactions->where('type', 'expense')
                        ->where('category', 'maintenance')
                        ->sum('amount'),
                    'utilities' => $transactions->where('type', 'expense')
                        ->where('category', 'utility')
                        ->sum('amount'),
                    'commission' => $this->calculateCommission($property, $reservations),
                    'total' => 0
                ]
            ];

            $propertyData['revenue']['total'] = $propertyData['revenue']['reservations'] +
                $propertyData['revenue']['other'];

            $propertyData['expenses']['total'] = $propertyData['expenses']['cleaning'] +
                $propertyData['expenses']['maintenance'] +
                $propertyData['expenses']['utilities'] +
                $propertyData['expenses']['commission'];

            $propertyData['net_amount'] = $propertyData['revenue']['total'] -
                $propertyData['expenses']['total'];

            $propertiesData[] = $propertyData;

            // Acumula totais
            $totals['revenue'] += $propertyData['revenue']['total'];
            $totals['expenses'] += $propertyData['expenses']['total'];
            $totals['net_amount'] += $propertyData['net_amount'];
        }

        return [
            'owner' => $owner,
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y')
            ],
            'properties' => $propertiesData,
            'totals' => $totals
        ];
    }

    public function exportOwnerReportPdf($ownerId, Carbon $startDate, Carbon $endDate)
    {
        $report = $this->generateOwnerReport($ownerId, $startDate, $endDate);

        $pdf = PDF::loadView('reports.pdf.owner', $report);

        return $pdf->download('relatorio-proprietario-' . $report['owner']->id . '.pdf');
    }

    public function exportOwnerReportExcel($ownerId, Carbon $startDate, Carbon $endDate)
    {
        $report = $this->generateOwnerReport($ownerId, $startDate, $endDate);

        return Excel::download(
            new OwnerReportExport($report),
            'relatorio-proprietario-' . $report['owner']->id . '.xlsx'
        );
    }

    protected function calculateCommission($property, $reservations)
    {
        return $reservations->sum(function ($reservation) use ($property) {
            return ($reservation->total_amount * $property->commission_percentage) / 100;
        });
    }
}
