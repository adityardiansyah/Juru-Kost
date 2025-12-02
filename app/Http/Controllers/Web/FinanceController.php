<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
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

        $incomeCategories = $incomes->groupBy('category.name')->map->sum('amount')->toArray();
        $expenseCategories = $expenses->groupBy('category.name')->map->sum('amount')->toArray();

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

    public function incomes(Request $request)
    {
        $incomes = Income::where('tenant_id', session('tenant_id'))
            ->with('category')
            ->when($request->month, function ($query, $month) {
                $date = \Carbon\Carbon::parse($month);
                $query->whereYear('transaction_date', $date->year)
                    ->whereMonth('transaction_date', $date->month);
            })
            ->latest('transaction_date')
            ->paginate(20);

        $categories = IncomeCategory::all();

        return view('finance.incomes.index', compact('incomes', 'categories'));
    }

    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'income_category_id' => 'required|exists:income_categories,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('proof_file')) {
            $validated['proof_file'] = $request->file('proof_file')->store('proofs', 'public');
        }

        $validated['tenant_id'] = session('tenant_id');

        Income::create($validated);

        return redirect()->route('finance.incomes.index')
            ->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function expenses(Request $request)
    {
        $expenses = Expense::where('tenant_id', session('tenant_id'))
            ->with('category')
            ->when($request->month, function ($query, $month) {
                $date = \Carbon\Carbon::parse($month);
                $query->whereYear('transaction_date', $date->year)
                    ->whereMonth('transaction_date', $date->month);
            })
            ->latest('transaction_date')
            ->paginate(20);

        $categories = ExpenseCategory::all();

        return view('finance.expenses.index', compact('expenses', 'categories'));
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('proof_file')) {
            $validated['proof_file'] = $request->file('proof_file')->store('proofs', 'public');
        }

        $validated['tenant_id'] = session('tenant_id');

        Expense::create($validated);

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function destroyIncome(Income $income)
    {
        $income->delete();

        return redirect()->route('finance.incomes.index')
            ->with('success', 'Pemasukan berhasil dihapus!');
    }

    public function destroyExpense(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
