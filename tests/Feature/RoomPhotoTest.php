<?php

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('can remove photo from room', function () {
    Storage::fake('public');

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

    $photo1 = UploadedFile::fake()->image('photo1.jpg');
    $photo2 = UploadedFile::fake()->image('photo2.jpg');

    $path1 = $photo1->store('rooms', 'public');
    $path2 = $photo2->store('rooms', 'public');

    $room = Room::create([
        'tenant_id' => $tenant->id,
        'room_type_id' => $roomType->id,
        'room_number' => '101',
        'price' => 1000000,
        'status' => 'available',
        'photos' => [$path1, $path2],
    ]);

    $response = $this->actingAs($user)
        ->withSession(['tenant_id' => $tenant->id])
        ->put(route('rooms.update', $room), [
            'room_number' => $room->room_number,
            'price' => $room->price,
            'status' => $room->status,
            'keep_photos' => json_encode([$path1]), // Keep only photo1
        ]);

    $response->assertRedirect();

    $room->refresh();

    expect($room->photos)->toHaveCount(1);
    expect($room->photos[0])->toBe($path1);

    Storage::disk('public')->assertExists($path1);
    Storage::disk('public')->assertMissing($path2);
});
