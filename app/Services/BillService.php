<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Resident;
use Carbon\Carbon;

class BillService
{
    /**
     * Generate monthly bills for all active residents
     */
    public function generateMonthlyBills(int $tenantId, Carbon $month): array
    {
        $activeResidents = Resident::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereHas('currentRoom')
            ->with('currentRoom.room')
            ->get();

        $generated = [];

        foreach ($activeResidents as $resident) {
            $roomLog = $resident->currentRoom;
            
            // Check if bill already exists
            $exists = Bill::where('tenant_id', $tenantId)
                ->where('resident_id', $resident->id)
                ->whereYear('bill_date', $month->year)
                ->whereMonth('bill_date', $month->month)
                ->exists();

            if ($exists) {
                continue;
            }

            // Create bill
            $bill = Bill::create([
                'tenant_id' => $tenantId,
                'resident_id' => $resident->id,
                'bill_date' => $month->startOfMonth(),
                'due_date' => $month->copy()->addDays(10),
                'total_amount' => 0,
                'paid_amount' => 0,
                'status' => 'unpaid',
            ]);

            // Add room rent item
            $bill->items()->create([
                'tenant_id' => $tenantId,
                'description' => 'Sewa Kamar ' . $roomLog->room->room_number,
                'amount' => $roomLog->monthly_price,
                'quantity' => 1,
                'subtotal' => $roomLog->monthly_price,
            ]);

            // Update total
            $bill->total_amount = $bill->items->sum('subtotal');
            $bill->save();

            $generated[] = $bill;
        }

        return $generated;
    }

    /**
     * Add additional charge to bill
     */
    public function addCharge(Bill $bill, string $description, float $amount, int $quantity = 1): void
    {
        $bill->items()->create([
            'tenant_id' => $bill->tenant_id,
            'description' => $description,
            'amount' => $amount,
            'quantity' => $quantity,
            'subtotal' => $amount * $quantity,
        ]);

        $bill->total_amount = $bill->items->sum('subtotal');
        $bill->save();
    }
}