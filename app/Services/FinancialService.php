<?php

namespace App\Services;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class FinancialService
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getTransactions(array $filters = [])
    {
        $cacheKey = 'transactions.' . md5(json_encode($filters));

        return Cache::remember($cacheKey, 3600, function () use ($filters) {
            return $this->transactionRepository->getAllWithFilters($filters);
        });
    }

    public function getCurrentMonthRevenue()
    {
        return Cache::remember('financial.current_month_revenue', 3600, function () {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            return $this->transactionRepository->getSumByDateRange(
                $startDate,
                $endDate,
                'income'
            );
        });
    }

    public function getCurrentMonthExpenses()
    {
        return Cache::remember('financial.current_month_expenses', 3600, function () {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            return $this->transactionRepository->getSumByDateRange(
                $startDate,
                $endDate,
                'expense'
            );
        });
    }

    public function createTransaction(array $data)
    {
        $transaction = $this->transactionRepository->create($data);
        $this->clearFinancialCache();
        return $transaction;
    }

    public function updateTransaction($id, array $data)
    {
        $transaction = $this->transactionRepository->update($id, $data);
        $this->clearFinancialCache();
        return $transaction;
    }

    public function deleteTransaction($id)
    {
        $result = $this->transactionRepository->delete($id);
        $this->clearFinancialCache();
        return $result;
    }

    public function generateReport(array $filters)
    {
        $startDate = Carbon::parse($filters['start_date']);
        $endDate = Carbon::parse($filters['end_date']);

        $transactions = $this->transactionRepository->getByDateRange($startDate, $endDate);

        if (isset($filters['property_id'])) {
            $transactions = $transactions->where('property_id', $filters['property_id']);
        }

        $report = [
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y')
            ],
            'totals' => [
                'income' => $transactions->where('type', 'income')->sum('amount'),
                'expense' => $transactions->where('type', 'expense')->sum('amount')
            ],
            'by_category' => [
                'income' => $transactions->where('type', 'income')
                    ->groupBy('category')
                    ->map(function ($group) {
                        return $group->sum('amount');
                    }),
                'expense' => $transactions->where('type', 'expense')
                    ->groupBy('category')
                    ->map(function ($group) {
                        return $group->sum('amount');
                    })
            ],
            'transactions' => $transactions
        ];

        $report['totals']['balance'] = $report['totals']['income'] - $report['totals']['expense'];

        return $report;
    }

    public function getTransactionsByProperty($propertyId, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        return $this->transactionRepository->getByProperty($propertyId, $startDate, $endDate);
    }

    public function getTransactionsByReservation($reservationId)
    {
        return $this->transactionRepository->getByReservation($reservationId);
    }

    public function calculateOwnerPayment($propertyId, $startDate, $endDate)
    {
        $transactions = $this->getTransactionsByProperty($propertyId, $startDate, $endDate);

        $revenue = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');
        $commission = $this->calculateCommission($propertyId, $revenue);

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'commission' => $commission,
            'net_amount' => $revenue - $expenses - $commission
        ];
    }

    protected function calculateCommission($propertyId, $revenue)
    {
        $property = app(PropertyService::class)->getProperty($propertyId);
        return ($revenue * $property->commission_percentage) / 100;
    }

    protected function clearFinancialCache()
    {
        Cache::tags(['financial'])->flush();
    }
}
