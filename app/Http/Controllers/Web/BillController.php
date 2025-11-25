<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Services\BillService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BillController extends Controller
{
    protected $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    public function index(Request $request)
    {
        $bills = Bill::with('resident', 'items', 'payments')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->month, function ($query, $month) {
                $date = \Carbon\Carbon::parse($month);
                $query->whereYear('bill_date', $date->year)
                    ->whereMonth('bill_date', $date->month);
            })
            ->latest('bill_date')
            ->paginate(20);

        // Statistics
        $stats = [
            'total_bills' => Bill::whereMonth('bill_date', now()->month)->count(),
            'paid' => Bill::where('status', 'paid')->whereMonth('bill_date', now()->month)->count(),
            'unpaid' => Bill::where('status', 'unpaid')->whereMonth('bill_date', now()->month)->count(),
            'overdue' => Bill::where('status', 'overdue')->whereMonth('bill_date', now()->month)->count(),
        ];

        return view('bills.index', compact('bills', 'stats'));
    }

    public function show(Bill $bill)
    {
        $bill->load('resident', 'items', 'payments');
        return view('bills.show', compact('bill'));
    }

    public function showGenerate()
    {
        return view('bills.generate');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = \Carbon\Carbon::parse($validated['month']);
        $bills = $this->billService->generateMonthlyBills(session('tenant_id'), $month);

        return redirect()->route('bills.index')
            ->with('success', count($bills) . ' tagihan berhasil digenerate!');
    }


    public function downloadPdf(Bill $bill)
    {
        $pdf = Pdf::loadView('bills.pdf', compact('bill'));
        return $pdf->download('tagihan-' . $bill->bill_number . '.pdf');
    }
}
