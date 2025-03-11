<?php

namespace App\Http\Controllers;

use App\Services\FinancialService;
use App\Services\PropertyService;
use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    protected $financialService;
    protected $propertyService;

    public function __construct(
        FinancialService $financialService,
        PropertyService $propertyService
    ) {
        $this->financialService = $financialService;
        $this->propertyService = $propertyService;
    }

    public function index(Request $request)
    {
        $data = [
            'monthly_revenue' => $this->financialService->getCurrentMonthRevenue(),
            'monthly_expenses' => $this->financialService->getCurrentMonthExpenses(),
            'transactions' => $this->financialService->getTransactions($request->all()),
            'properties' => $this->propertyService->getAllProperties()
        ];

        return view('financial.index', $data);
    }

    public function transactions()
    {
        $properties = $this->propertyService->getAllProperties();
        return view('financial.transactions', compact('properties'));
    }

    public function storeTransaction(TransactionRequest $request)
    {
        $this->financialService->createTransaction($request->validated());
        return redirect()->route('financial.index')
            ->with('success', 'Transação registrada com sucesso.');
    }

    public function updateTransaction(TransactionRequest $request, $id)
    {
        $this->financialService->updateTransaction($id, $request->validated());
        return redirect()->route('financial.index')
            ->with('success', 'Transação atualizada com sucesso.');
    }

    public function destroyTransaction($id)
    {
        $this->financialService->deleteTransaction($id);
        return redirect()->route('financial.index')
            ->with('success', 'Transação removida com sucesso.');
    }

    public function report(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'property_id' => 'nullable|exists:properties,id'
        ]);

        $report = $this->financialService->generateReport($request->all());
        return view('financial.report', compact('report'));
    }
}
