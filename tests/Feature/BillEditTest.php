<?php

use App\Models\Bill;
use App\Models\Resident;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Tenant;
use App\Models\User;

test('can edit unpaid bill', function () {
    $user = User::factory()->create();
    $tenant = Tenant::create([
        'name' => 'Test Kost',
        'slug' => 'test-kost',
        'address' => 'Test Address',
        'phone' => '08123456789',
        'email' => 'test@kost.com',
        'is_active' => true,
    ]);

    $user->tenants()->attach($tenant, ['role' => 'owner']);

    $roomType = RoomType::create([
        'name' => 'Standard',
        'description' => 'Standard Room',
    ]);

    $room = Room::create([
        'tenant_id' => $tenant->id,
        'room_type_id' => $roomType->id,
        'room_number' => '101',
        'price' => 1000000,
        'status' => 'occupied',
    ]);

    $resident = Resident::create([
        'tenant_id' => $tenant->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '08123456789',
        'status' => 'active',
        'start_date' => now(),
    ]);

    $resident->roomLogs()->create([
        'room_id' => $room->id,
        'start_date' => now(),
        'price' => 1000000,
    ]);

    $bill = Bill::create([
        'tenant_id' => $tenant->id,
        'resident_id' => $resident->id,
        'bill_date' => now(),
        'due_date' => now()->addDays(7),
        'total_amount' => 1000000,
        'paid_amount' => 0,
        'status' => 'unpaid',
    ]);

    $bill->items()->create([
        'tenant_id' => $tenant->id,
        'description' => 'Sewa Kamar',
        'amount' => 1000000,
        'quantity' => 1,
        'subtotal' => 1000000,
    ]);

    $response = $this->actingAs($user)
        ->withSession(['tenant_id' => $tenant->id])
        ->put(route('bills.update', $bill), [
            'resident_id' => $resident->id,
            'bill_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'items' => [
                [
                    'description' => 'Sewa Kamar Updated',
                    'amount' => 1200000,
                    'quantity' => 1,
                ]
            ],
        ]);

    $response->assertRedirect();

    $bill->refresh();

    expect($bill->total_amount)->toBe(1200000.00);
    expect($bill->items->first()->description)->toBe('Sewa Kamar Updated');
});

test('can delete unpaid bill', function () {
    $user = User::factory()->create();
    $tenant = Tenant::create([
        'name' => 'Test Kost',
        'slug' => 'test-kost',
        'address' => 'Test Address',
        'phone' => '08123456789',
        'email' => 'test@kost.com',
        'is_active' => true,
    ]);

    $user->tenants()->attach($tenant, ['role' => 'owner']);

    $roomType = RoomType::create([
        'name' => 'Standard',
        'description' => 'Standard Room',
    ]);

    $room = Room::create([
        'tenant_id' => $tenant->id,
        'room_type_id' => $roomType->id,
        'room_number' => '102',
        'price' => 1000000,
        'status' => 'occupied',
    ]);

    $resident = Resident::create([
        'tenant_id' => $tenant->id,
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '08123456789',
        'status' => 'active',
        'start_date' => now(),
    ]);

    $resident->roomLogs()->create([
        'room_id' => $room->id,
        'start_date' => now(),
        'price' => 1000000,
    ]);

    $bill = Bill::create([
        'tenant_id' => $tenant->id,
        'resident_id' => $resident->id,
        'bill_date' => now(),
        'due_date' => now()->addDays(7),
        'total_amount' => 1000000,
        'paid_amount' => 0,
        'status' => 'unpaid',
    ]);

    $response = $this->actingAs($user)
        ->withSession(['tenant_id' => $tenant->id])
        ->delete(route('bills.destroy', $bill));

    $response->assertRedirect();

    expect(Bill::find($bill->id))->toBeNull();
});
