<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Resident;
use App\Models\Room;
use Carbon\Carbon;

// app/Services/CostService.php
class CostService
{
    /**
     * Hitung HPP (Harga Pokok Pengelolaan) per kamar
     */
    public function calculateHPP(int $tenantId, Carbon $month): array
    {
        $expenses = Expense::where('tenant_id', $tenantId)
            ->whereYear('transaction_date', $month->year)
            ->whereMonth('transaction_date', $month->month)
            ->sum('amount');

        $totalRooms = Room::where('tenant_id', $tenantId)->count();
        
        $hppPerRoom = $totalRooms > 0 ? $expenses / $totalRooms : 0;

        return [
            'total_expenses' => $expenses,
            'total_rooms' => $totalRooms,
            'hpp_per_room' => round($hppPerRoom, 2),
        ];
    }

    /**
     * Hitung BEP (Break Even Point)
     */
    public function calculateBEP(int $tenantId): array
    {
        // Fixed cost (biaya tetap bulanan)
        $fixedCosts = Expense::where('tenant_id', $tenantId)
            ->whereIn('expense_category_id', [1, 2, 3]) // Gaji, Listrik, Air
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        // Average room price
        $avgRoomPrice = Room::where('tenant_id', $tenantId)->avg('price');
        
        // Variable cost per room (sekitar 20% dari harga kamar)
        $variableCostPerRoom = $avgRoomPrice * 0.2;

        // BEP in units
        $bepUnits = $avgRoomPrice > $variableCostPerRoom 
            ? $fixedCosts / ($avgRoomPrice - $variableCostPerRoom)
            : 0;

        // Total rooms
        $totalRooms = Room::where('tenant_id', $tenantId)->count();
        
        // BEP percentage
        $bepPercentage = $totalRooms > 0 ? ($bepUnits / $totalRooms) * 100 : 0;

        return [
            'fixed_costs' => round($fixedCosts, 2),
            'variable_cost_per_room' => round($variableCostPerRoom, 2),
            'avg_room_price' => round($avgRoomPrice, 2),
            'bep_units' => round($bepUnits, 2),
            'bep_percentage' => round($bepPercentage, 2),
            'total_rooms' => $totalRooms,
        ];
    }

    /**
     * Hitung ROI (Return on Investment)
     */
    public function calculateROI(int $tenantId, Carbon $startDate, Carbon $endDate): array
    {
        $totalIncome = Income::where('tenant_id', $tenantId)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpense = Expense::where('tenant_id', $tenantId)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpense;
        
        $roi = $totalExpense > 0 ? ($netProfit / $totalExpense) * 100 : 0;

        return [
            'total_income' => round($totalIncome, 2),
            'total_expense' => round($totalExpense, 2),
            'net_profit' => round($netProfit, 2),
            'roi_percentage' => round($roi, 2),
            'period' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
        ];
    }
}
