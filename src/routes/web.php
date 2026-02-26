<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontOrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\LaporanController;

Route::get('/', fn() => redirect('/s/01'));

Route::prefix('s')->group(function () {
    Route::get('/{table_number}',          [FrontOrderController::class, 'menu'])->name('front.menu');
    Route::post('/{table_number}/order',   [FrontOrderController::class, 'store'])->name('front.order.store');
    Route::get('/{table_number}/status',   [StatusController::class, 'page'])->name('front.status');
    Route::get('/{table_number}/tracking', [StatusController::class, 'tracking'])->name('front.tracking');
});

Route::get('/status/order/{order_id}', [StatusController::class, 'check'])->name('status.check');
Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
Route::get('/laporan',   [LaporanController::class, 'index'])->name('laporan');

Route::prefix('admin-api')->group(function () {
    Route::get   ('/orders',                [AdminController::class, 'orders']);
    Route::patch ('/orders/{order}/status', [AdminController::class, 'updateOrderStatus']);
    Route::get   ('/products',              [AdminController::class, 'products']);
    Route::post  ('/products',              [AdminController::class, 'storeProduct']);
    Route::put   ('/products/{product}',    [AdminController::class, 'updateProduct']);
    Route::delete('/products/{product}',    [AdminController::class, 'deleteProduct']);
    Route::get   ('/laporan',               [LaporanController::class, 'data']);
    Route::get   ('/laporan/export-excel',  [LaporanController::class, 'exportExcel']);
    Route::get   ('/laporan/export-pdf',    [LaporanController::class, 'exportPDF']);
});
