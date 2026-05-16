<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionHistoryController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\TableController;
use App\Http\Controllers\POS\TransactionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin + Kasir Routes (semua role bisa akses)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard — semua bisa
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menu — kasir read only (hanya index & show)
    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('menus/{menu}', [MenuController::class, 'show'])->name('menus.show');

    // Categories — kasir read only
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');

    // Ingredients / Bahan — kasir read only
    Route::get('ingredients', [IngredientController::class, 'index'])->name('ingredients.index');

    // Inventory — kasir hanya lihat
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Reports / Laporan transaksi — kasir boleh lihat transaksi saja
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('reports/transactions/export/pdf', [ReportController::class, 'exportTransactionPdf'])->name('reports.transactions.pdf');
    Route::get('reports/transactions/export/excel', [ReportController::class, 'exportTransactionExcel'])->name('reports.transactions.excel');

    // Data Transaksi History (halaman baru)
    Route::get('transactions', [TransactionHistoryController::class, 'index'])->name('transactions.index');
    Route::get('transactions/export/pdf', [TransactionHistoryController::class, 'exportPdf'])->name('transactions.pdf');
    Route::get('transactions/export/excel', [TransactionHistoryController::class, 'exportExcel'])->name('transactions.excel');
    Route::get('transactions/{transaction}', [TransactionHistoryController::class, 'show'])->name('transactions.show');

    /*
    |--------------------------------------------------------------------------
    | Admin-only Routes (kasir DIBLOKIR)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin.only'])->group(function () {

        // Menu CRUD (admin only)
        Route::get('menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

        // Categories CRUD (admin only)
        Route::resource('categories', CategoryController::class)->except(['index']);

        // Ingredients CRUD (admin only)
        Route::get('ingredients/create', [IngredientController::class, 'create'])->name('ingredients.create');
        Route::post('ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::get('ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit');
        Route::put('ingredients/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
        Route::delete('ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');

        // Inventory input stok (admin only)
        Route::get('inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');

        // Suppliers (admin only)
        Route::resource('suppliers', SupplierController::class);

        // Users (admin only)
        Route::resource('users', UserController::class);

        // Reports inventory (admin only)
        Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('reports/inventory/export/pdf', [ReportController::class, 'exportInventoryPdf'])->name('reports.inventory.pdf');
        Route::get('reports/inventory/export/excel', [ReportController::class, 'exportInventoryExcel'])->name('reports.inventory.excel');

        // Settings (admin only)
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('settings/printer', [SettingController::class, 'printer'])->name('settings.printer');
        Route::post('settings/printer', [SettingController::class, 'updatePrinter'])->name('settings.printer.update');
    });
});

/*
|--------------------------------------------------------------------------
| POS Routes — semua role bisa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [POSController::class, 'index'])->name('index');
    Route::get('/tables', [TableController::class, 'index'])->name('tables');

    Route::post('/transaction/start', [TransactionController::class, 'start'])->name('transaction.start');
    Route::get('/transaction/{transaction}', [TransactionController::class, 'show'])->name('transaction.show');
    Route::post('/transaction/{transaction}/add-item', [TransactionController::class, 'addItem'])->name('transaction.add-item');
    Route::put('/transaction/{transaction}/item/{item}', [TransactionController::class, 'updateItem'])->name('transaction.update-item');
    Route::delete('/transaction/{transaction}/item/{item}', [TransactionController::class, 'removeItem'])->name('transaction.remove-item');
    Route::post('/transaction/{transaction}/hold', [TransactionController::class, 'hold'])->name('transaction.hold');
    Route::post('/transaction/{transaction}/checkout', [TransactionController::class, 'checkout'])->name('transaction.checkout');
    Route::post('/transaction/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transaction.cancel');
    Route::get('/transaction/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transaction.receipt');
    Route::get('/transaction/{transaction}/print', [TransactionController::class, 'printReceipt'])->name('transaction.print');

    Route::get('/menus', [POSController::class, 'menus'])->name('menus');
});
