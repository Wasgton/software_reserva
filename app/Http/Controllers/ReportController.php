<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\OwnerService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;
    protected $ownerService;

    public function __construct(
        ReportService $reportService,
        OwnerService $ownerService
    ) {
        $this->reportService = $reportService;
        $this->ownerService = $ownerService;
    }

    public function owners(Request $request)
    {
        $owners = $this->ownerService->getAllOwners();

        $report = null;
        if ($request->filled('owner_id')) {
            $dateRange = $this->getDateRange($request->get('period'));
            $report = $this->reportService->generateOwnerReport(
                $request->get('owner_id'),
                $dateRange['start'],
                $dateRange['end']
            );
        }

        return view('reports.owners', compact('owners', 'report'));
    }

    public function exportOwnerReport(Request $request, $owner)
    {
        $this->validate($request, [
            'format' => 'required|in:pdf,excel'
        ]);

        $dateRange = $this->getDateRange($request->get('period', 'current_month'));

        if ($request->format === 'pdf') {
            return $this->reportService->exportOwnerReportPdf(
                $owner,
                $dateRange['start'],
                $dateRange['end']
            );
        }

        return $this->reportService->exportOwnerReportExcel(
            $owner,
            $dateRange['start'],
            $dateRange['end']
        );
    }

    protected function getDateRange($period = 'current_month'): array
    {
        switch ($period) {
            case 'last_month':
                return [
                    'start' => Carbon::now()->subMonth()->startOfMonth(),
                    'end' => Carbon::now()->subMonth()->endOfMonth()
                ];
            case 'custom':
                return [
                    'start' => Carbon::parse(request('start_date', Carbon::now()->startOfMonth())),
                    'end' => Carbon::parse(request('end_date', Carbon::now()->endOfMonth()))
                ];
            default: // current_month
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth()
                ];
        }
    }
}
