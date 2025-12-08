<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Services\DebtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DebtController extends Controller
{
    protected $debtService;

    public function __construct(DebtService $debtService)
    {
        $this->debtService = $debtService;
    }

    /**
     * Display a listing of debts
     */
    public function index(Request $request)
    {
        $tenantId = session('tenant_id');

        // Get debts with optional status filter
        $query = Debt::where('tenant_id', $tenantId)->with('payments');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $debts = $query->latest()->paginate(10);

        // Get summary
        $summary = $this->debtService->getDebtSummary($tenantId);

        // Get upcoming payments
        $upcomingPayments = $this->debtService->getUpcomingPayments($tenantId, 30);

        return view('debts.index', compact('debts', 'summary', 'upcomingPayments'));
    }

    /**
     * Show the form for creating a new debt
     */
    public function create()
    {
        $debtTypes = [
            'bank' => 'Bank',
            'supplier' => 'Supplier',
            'personal' => 'Personal',
            'other' => 'Lainnya'
        ];

        $frequencies = [
            'monthly' => 'Bulanan',
            'quarterly' => 'Per 3 Bulan',
            'yearly' => 'Tahunan'
        ];

        return view('debts.create', compact('debtTypes', 'frequencies'));
    }

    /**
     * Store a newly created debt
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'creditor_name' => 'required|string|max:255',
            'debt_type' => 'required|in:bank,supplier,personal,other',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'total_installments' => 'required|integer|min:1',
            'installment_frequency' => 'required|in:monthly,quarterly,yearly',
            'description' => 'nullable|string',
            'contract_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['tenant_id'] = session('tenant_id');
        $validated['generate_schedule'] = $request->has('generate_schedule');

        // Handle file upload
        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }

        // Calculate end date based on frequency and installments
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $totalInstallments = (int) $validated['total_installments'];
        switch ($validated['installment_frequency']) {
            case 'monthly':
                $validated['end_date'] = $startDate->copy()->addMonths($totalInstallments);
                break;
            case 'quarterly':
                $validated['end_date'] = $startDate->copy()->addMonths($totalInstallments * 3);
                break;
            case 'yearly':
                $validated['end_date'] = $startDate->copy()->addYears($totalInstallments);
                break;
        }

        $debt = $this->debtService->createDebt($validated);

        return redirect()->route('debts.show', $debt)
            ->with('success', 'Hutang berhasil ditambahkan!');
    }

    /**
     * Display the specified debt with payment schedule
     */
    public function show(Debt $debt)
    {
        $debt->load('payments');

        // Get amortization schedule
        $schedule = $debt->calculateAmortization();

        // Match schedule with actual payments
        foreach ($schedule as &$item) {
            $payment = $debt->payments()
                ->whereDate('due_date', $item['due_date'])
                ->first();
            $item['payment'] = $payment;
        }

        return view('debts.show', compact('debt', 'schedule'));
    }

    /**
     * Show the form for editing the specified debt
     */
    public function edit(Debt $debt)
    {
        $debtTypes = [
            'bank' => 'Bank',
            'supplier' => 'Supplier',
            'personal' => 'Personal',
            'other' => 'Lainnya'
        ];

        $frequencies = [
            'monthly' => 'Bulanan',
            'quarterly' => 'Per 3 Bulan',
            'yearly' => 'Tahunan'
        ];

        return view('debts.edit', compact('debt', 'debtTypes', 'frequencies'));
    }

    /**
     * Update the specified debt
     */
    public function update(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'creditor_name' => 'required|string|max:255',
            'debt_type' => 'required|in:bank,supplier,personal,other',
            'description' => 'nullable|string',
            'contract_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('contract_file')) {
            // Delete old file if exists
            if ($debt->contract_file) {
                Storage::disk('public')->delete($debt->contract_file);
            }
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }

        $debt->update($validated);

        return redirect()->route('debts.show', $debt)
            ->with('success', 'Hutang berhasil diperbarui!');
    }

    /**
     * Remove the specified debt
     */
    public function destroy(Debt $debt)
    {
        // Check if debt has payments
        if ($debt->payments()->where('status', 'paid')->count() > 0) {
            return redirect()->route('debts.index')
                ->with('error', 'Tidak dapat menghapus hutang yang sudah memiliki pembayaran!');
        }

        // Delete contract file if exists
        if ($debt->contract_file) {
            Storage::disk('public')->delete($debt->contract_file);
        }

        $debt->delete();

        return redirect()->route('debts.index')
            ->with('success', 'Hutang berhasil dihapus!');
    }
}
