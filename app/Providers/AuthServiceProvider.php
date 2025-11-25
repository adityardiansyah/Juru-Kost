<?php

namespace App\Providers;

use App\Models\{Tenant, Room, Resident, Bill, Asset};
use App\Policies\{TenantPolicy, RoomPolicy, ResidentPolicy, BillPolicy, AssetPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Tenant::class => TenantPolicy::class,
        Room::class => RoomPolicy::class,
        // Resident::class => ResidentPolicy::class,
        // Bill::class => BillPolicy::class,
        // Asset::class => AssetPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
