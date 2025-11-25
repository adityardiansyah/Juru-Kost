<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function index(Request $request)
    {
        $month = $request->month ? \Carbon\Carbon::parse($request->month) : now();
        $tenantId = session('tenant_id');

        $summary = $this->financeService->getCashflowSummary($tenantId, $month);

        $incomes = Income::where('tenant_id', $tenantId)
            ->whereYear('transaction_date', $month->year)
            ->whereMonth('transaction_date', $month->month)
            ->with('category')
            ->get();

        $expenses = Expense::where('tenant_id', $tenantId)
            ->whereYear('transaction_date', $month->year)
            ->whereMonth('transaction_date', $month->month)
            ->with('category')
            ->get();

        $incomeCategories = $incomes->groupBy('category.name')->map->sum('amount');
        $expenseCategories = $expenses->groupBy('category.name')->map->sum('amount');

        $roi = app(\App\Services\CostService::class)
            ->calculateROI($tenantId, $month->startOfMonth(), $month->endOfMonth());

        return view('finance.index', compact(
            'summary',
            'incomes',
            'expenses',
            'incomeCategories',
            'expenseCategories',
            'roi'
        ));
    }
}
