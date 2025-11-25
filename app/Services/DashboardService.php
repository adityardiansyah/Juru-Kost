<?php

namespace App\Services;

use App\Models\Asset;;

use App\Models\Bill;
use App\Models\Resident;
use App\Models\Room;

class DashboardService
{
    /**
     * Get dashboard statistics
     */
    public function getStatistics(int $tenantId): array
    {
        $totalRooms = Room::where('tenant_id', $tenantId)->count();
        $occupiedRooms = Room::where('tenant_id', $tenantId)
            ->where('status', 'occupied')
            ->count();

        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        $activeResidents = Resident::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->count();

        $unpaidBills = Bill::where('tenant_id', $tenantId)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->count();

        $totalUnpaidAmount = Bill::where('tenant_id', $tenantId)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->sum('total_amount');

        $monthlyIncome = \App\Models\Income::where('tenant_id', $tenantId)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $monthlyExpense = \App\Models\Expense::where('tenant_id', $tenantId)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        return [
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'available_rooms' => $totalRooms - $occupiedRooms,
            'occupancy_rate' => round($occupancyRate, 2),
            'active_residents' => $activeResidents,
            'unpaid_bills' => $unpaidBills,
            'total_unpaid_amount' => round($totalUnpaidAmount, 2),
            'monthly_income' => round($monthlyIncome, 2),
            'monthly_expense' => round($monthlyExpense, 2),
            'monthly_profit' => round($monthlyIncome - $monthlyExpense, 2),
        ];
    }

    /**
     * Get assets near end of life
     */
    public function getAssetsNearEndOfLife(int $tenantId, int $yearsThreshold = 1): array
    {
        return Asset::where('tenant_id', $tenantId)
            ->whereRaw('EXTRACT(YEAR FROM AGE(NOW(), purchase_date)) >= useful_life_years - ?', [$yearsThreshold])
            ->with('room')
            ->get()
            ->toArray();
    }

    /**
     * Get low stock inventory
     */
    public function getLowStockInventory(int $tenantId): array
    {
        return \App\Models\Inventory::where('tenant_id', $tenantId)
            ->whereColumn('quantity', '<=', 'min_stock')
            ->get()
            ->toArray();
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $tenantId, int $limit = 10): array
    {
        $activities = [];

        // Recent payments
        $payments = \App\Models\Payment::where('tenant_id', $tenantId)
            ->with('bill.resident')
            ->latest()
            ->limit($limit)
            ->get();

        foreach ($payments as $payment) {
            $activities[] = [
                'type' => 'payment',
                'description' => 'Pembayaran dari ' . $payment->bill->resident->name,
                'amount' => $payment->amount,
                'date' => $payment->created_at,
            ];
        }

        // Recent expenses
        $expenses = \App\Models\Expense::where('tenant_id', $tenantId)
            ->with('category')
            ->latest()
            ->limit($limit)
            ->get();

        foreach ($expenses as $expense) {
            $activities[] = [
                'type' => 'expense',
                'description' => $expense->category->name . ': ' . $expense->description,
                'amount' => -$expense->amount,
                'date' => $expense->created_at,
            ];
        }

        // Sort by date
        usort($activities, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, $limit);
    }
}
