<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $tenantId = session('tenant_id');

        $statistics = $this->dashboardService->getStatistics($tenantId);
        $lowStock = $this->dashboardService->getLowStockInventory($tenantId);
        $assetsNearEnd = $this->dashboardService->getAssetsNearEndOfLife($tenantId);
        $activities = $this->dashboardService->getRecentActivities($tenantId);

        return view('dashboard.index', compact(
            'statistics',
            'lowStock',
            'assetsNearEnd',
            'activities'
        ));
    }
}
