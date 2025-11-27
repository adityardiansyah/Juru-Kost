<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Resident;
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

    public function create()
    {
        $residents = Resident::where('tenant_id', session('tenant_id'))->where('status', 'active')->get();
        return view('bills.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'bill_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:bill_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $bill = Bill::create([
            'tenant_id' => session('tenant_id'),
            'resident_id' => $validated['resident_id'],
            'bill_date' => $validated['bill_date'],
            'due_date' => $validated['due_date'],
            'total_amount' => 0,
            'paid_amount' => 0,
            'status' => 'unpaid',
        ]);

        // Add items
        foreach ($validated['items'] as $item) {
            $bill->items()->create([
                'tenant_id' => session('tenant_id'),
                'description' => $item['description'],
                'amount' => $item['amount'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['amount'] * $item['quantity'],
            ]);
        }

        // Update total
        $bill->total_amount = $bill->items->sum('subtotal');
        $bill->save();

        return redirect()->route('bills.index')
            ->with('success', 'Tagihan berhasil dibuat!');
    }

    public function show(Bill $bill)
    {
        $bill->load('resident', 'items', 'payments');
        return view('bills.show', compact('bill'));
    }

    public function showGenerate()
    {
        $activeResidents = Resident::where('tenant_id', session('tenant_id'))->where('status', 'active')->get();
        return view('bills.generate', compact('activeResidents'));
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
