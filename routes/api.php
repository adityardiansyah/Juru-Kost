<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    TenantController,
    RoomController,
    ResidentController,
    BillController,
    PaymentController,
    AssetController,
    InventoryController,
    MaintenanceRequestController,
    IncomeController,
    ExpenseController,
    DashboardController,
};

// Public routes
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);
Route::get('test', function() {
    return response()->json(['message' => 'API is working']);
});
// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Tenant management
    Route::prefix('tenants')->group(function () {
        Route::get('/', [TenantController::class, 'index']);
        Route::post('/', [TenantController::class, 'store']);
        Route::get('/{tenant}', [TenantController::class, 'show']);
        Route::put('/{tenant}', [TenantController::class, 'update']);
        Route::post('/{tenant}/switch', [TenantController::class, 'switchTenant']);
    });

    // Routes that require tenant selection
    // Route::middleware(['tenant.ensure'])->group(function () {

    //     // Dashboard
    //     Route::get('/dashboard', [DashboardController::class, 'index']);
    //     Route::get('/dashboard/statistics', [DashboardController::class, 'statistics']);
    //     Route::get('/dashboard/charts', [DashboardController::class, 'charts']);

    //     // Rooms
    //     Route::apiResource('rooms', RoomController::class);
    //     Route::get('/rooms/{room}/history', [RoomController::class, 'history']);

    //     // Residents
    //     Route::apiResource('residents', ResidentController::class);
    //     Route::post('/residents/{resident}/documents', [ResidentController::class, 'uploadDocument']);
    //     Route::get('/residents/{resident}/bills', [ResidentController::class, 'bills']);

    //     // Bills
    //     Route::apiResource('bills', BillController::class);
    //     Route::post('/bills/generate-monthly', [BillController::class, 'generateMonthly']);
    //     Route::post('/bills/{bill}/add-charge', [BillController::class, 'addCharge']);
    //     Route::get('/bills/{bill}/pdf', [BillController::class, 'downloadPdf']);

    //     // Payments
    //     Route::apiResource('payments', PaymentController::class)->only(['index', 'store', 'show']);

    //     // Income & Expenses
    //     Route::apiResource('incomes', IncomeController::class);
    //     Route::apiResource('expenses', ExpenseController::class);
    //     Route::get('/finance/summary', [FinanceController::class, 'summary']);
    //     Route::get('/finance/cashflow', [FinanceController::class, 'cashflow']);
    //     Route::get('/finance/hpp', [FinanceController::class, 'hpp']);
    //     Route::get('/finance/bep', [FinanceController::class, 'bep']);
    //     Route::get('/finance/roi', [FinanceController::class, 'roi']);

    //     // Assets
    //     Route::apiResource('assets', AssetController::class);
    //     Route::post('/assets/{asset}/maintenance', [AssetController::class, 'addMaintenance']);
    //     Route::get('/assets/{asset}/qrcode', [AssetController::class, 'getQrCode']);
    //     Route::post('/assets/depreciation/calculate', [AssetController::class, 'calculateDepreciation']);

    //     // Inventory
    //     Route::apiResource('inventories', InventoryController::class);
    //     Route::post('/inventories/{inventory}/stock-in', [InventoryController::class, 'stockIn']);
    //     Route::post('/inventories/{inventory}/stock-out', [InventoryController::class, 'stockOut']);
    //     Route::get('/inventories/low-stock/alert', [InventoryController::class, 'lowStockAlert']);

    //     // Maintenance Requests
    //     Route::apiResource('maintenance-requests', MaintenanceRequestController::class);
    //     Route::post('/maintenance-requests/{maintenanceRequest}/update-status', [MaintenanceRequestController::class, 'updateStatus']);

    //     // Reports
    //     Route::prefix('reports')->group(function () {
    //         Route::get('/occupancy', [ReportController::class, 'occupancy']);
    //         Route::get('/revenue', [ReportController::class, 'revenue']);
    //         Route::get('/expenses', [ReportController::class, 'expenses']);
    //         Route::get('/unpaid-bills', [ReportController::class, 'unpaidBills']);
    //         Route::get('/asset-depreciation', [ReportController::class, 'assetDepreciation']);
    //     });
    // });
});
