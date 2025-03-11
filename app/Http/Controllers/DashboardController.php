<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use App\Services\FinancialService;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected $reservationService;
    protected $financialService;

    public function __construct(
        ReservationService $reservationService,
        FinancialService $financialService
    ) {
        $this->reservationService = $reservationService;
        $this->financialService = $financialService;
    }

    public function index()
    {
        $data = Cache::remember('dashboard.data', 1800, function () {
            return [
                'current_reservations' => $this->reservationService->getCurrentReservations(),
                'upcoming_reservations' => $this->reservationService->getUpcomingReservations(),
                'monthly_revenue' => $this->financialService->getCurrentMonthRevenue(),
                'monthly_expenses' => $this->financialService->getCurrentMonthExpenses(),
                'occupancy_rate' => $this->reservationService->getCurrentOccupancyRate(),
            ];
        });

        return view('dashboard.index', $data);
    }
}
