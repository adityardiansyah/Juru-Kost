<?php

use App\Models\Asset;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Tenant;
use App\Models\User;

test('can create asset for room', function () {
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
        'status' => 'available',
    ]);

    $response = $this->actingAs($user)
        ->withSession(['tenant_id' => $tenant->id])
        ->post(route('assets.store'), [
            'room_id' => $room->id,
            'name' => 'AC Panasonic',
            'purchase_price' => 3000000,
            'purchase_date' => now()->format('Y-m-d'),
            'useful_life_years' => 5,
            'condition' => 'good',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('assets', [
        'name' => 'AC Panasonic',
        'room_id' => $room->id,
    ]);
});

test('can update asset', function () {
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

    $asset = Asset::create([
        'tenant_id' => $tenant->id,
        'name' => 'Old AC',
        'purchase_price' => 3000000,
        'purchase_date' => now()->subYears(1),
        'useful_life_years' => 5,
        'condition' => 'good',
        'current_value' => 2400000,
    ]);

    $response = $this->actingAs($user)
        ->withSession(['tenant_id' => $tenant->id])
        ->put(route('assets.update', $asset), [
            'name' => 'Updated AC',
            'purchase_price' => 3000000,
            'purchase_date' => now()->subYears(1)->format('Y-m-d'),
            'useful_life_years' => 5,
            'condition' => 'fair',
        ]);

    $response->assertRedirect();

    $asset->refresh();
    expect($asset->name)->toBe('Updated AC');
    expect($asset->condition)->toBe('fair');
});

test('can delete asset', function () {
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

    $asset = Asset::create([
        'tenant_id' => $tenant->id,
        'name' => 'To Delete',
        'purchase_price' => 1000000,
        'purchase_date' => now(),
        'useful_life_years' => 5,
        'condition' => 'good',
    ]);

    $response = $this->actingAs($user)
        ->withSession(['tenant_id' => $tenant->id])
        ->delete(route('assets.destroy', $asset));

    $response->assertRedirect();

    expect(Asset::find($asset->id))->toBeNull();
});
