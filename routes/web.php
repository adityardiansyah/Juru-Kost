<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\Web\BillController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\FinanceController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ResidentController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\TenantSelectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('tenant.ensure')->group(function () {
    Route::get('/tenant/{tenant}', [TenantController::class, 'show'])->name('tenant.show');
    Route::get('/tenant/{tenant}/edit', [TenantController::class, 'edit'])->name('tenant.edit');
    Route::put('/tenant/{tenant}', [TenantController::class, 'update'])->name('tenant.update');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'tenant.set'])->group(function () {

    // Tenant selection
    Route::get('/select-tenant', [TenantSelectionController::class, 'index'])->name('tenant.select');
    Route::post('/select-tenant', [TenantSelectionController::class, 'switch'])->name('tenant.switch');
    Route::get('/tenant/create', [TenantSelectionController::class, 'create'])->name('tenant.create');
    Route::post('/tenant', [TenantSelectionController::class, 'store'])->name('tenant.store');

    // Routes that require tenant
    Route::middleware('tenant.ensure')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('room-types', RoomTypeController::class);
        Route::resource('rooms', RoomController::class);

        // Residents
        Route::resource('residents', ResidentController::class);
        Route::post('residents/{resident}/documents', [ResidentController::class, 'uploadDocument'])
            ->name('residents.upload-document');
        Route::post('residents/{resident}/move-room', [ResidentController::class, 'moveRoom'])
            ->name('residents.move-room');

        // Bills
        Route::resource('bills', BillController::class);
        Route::get('bills-generate', [BillController::class, 'showGenerate'])->name('bills.generate');
        Route::post('bills-generate', [BillController::class, 'generate'])->name('bills.generate.process');
        Route::get('bills/{bill}/download-pdf', [BillController::class, 'downloadPdf'])
            ->name('bills.download-pdf');
        Route::post('bills/{bill}/add-charge', [BillController::class, 'addCharge'])
            ->name('bills.add-charge');

        // Payments
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

        // Finance
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [FinanceController::class, 'index'])->name('index');
            Route::get('/incomes', [FinanceController::class, 'incomes'])->name('incomes.index');
            Route::post('/incomes', [FinanceController::class, 'storeIncome'])->name('incomes.store');
            Route::get('/expenses', [FinanceController::class, 'expenses'])->name('expenses.index');
            Route::post('/expenses', [FinanceController::class, 'storeExpense'])->name('expenses.store');
            Route::get('/hpp', [FinanceController::class, 'hpp'])->name('hpp');
            Route::get('/export', [FinanceController::class, 'export'])->name('export');
        });

        // Assets
        Route::resource('assets', AssetController::class);
        Route::post('assets/{asset}/maintenance', [AssetController::class, 'addMaintenance'])
            ->name('assets.add-maintenance');
        Route::get('assets/{asset}/qrcode', [AssetController::class, 'downloadQrCode'])
            ->name('assets.qrcode');

        // Inventory
        Route::resource('inventories', InventoryController::class);
        Route::post('inventories/{inventory}/stock-in', [InventoryController::class, 'stockIn'])
            ->name('inventories.stock-in');
        Route::post('inventories/{inventory}/stock-out', [InventoryController::class, 'stockOut'])
            ->name('inventories.stock-out');

        // Maintenance Requests
        Route::resource('maintenance-requests', MaintenanceRequestController::class);
        Route::post(
            'maintenance-requests/{maintenanceRequest}/update-status',
            [MaintenanceRequestController::class, 'updateStatus']
        )
            ->name('maintenance-requests.update-status');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/occupancy', [ReportController::class, 'occupancy'])->name('occupancy');
            Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('/expenses', [ReportController::class, 'expenses'])->name('expenses');
            Route::get('/unpaid-bills', [ReportController::class, 'unpaidBills'])->name('unpaid-bills');
            Route::get('/assets', [ReportController::class, 'assets'])->name('assets');
        });
    });
});
