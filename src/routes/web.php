<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MachineController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\ToolCategoryController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MagazineController;
use App\Http\Controllers\Admin\StandbyController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\LpController;


Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

Route::get('/lp', [LpController::class, 'index'])->name('lp');

Route::middleware(['auth'])->prefix('app')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 在庫管理
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::middleware('admin')->group(function () {
        Route::put('/inventory/{tool}',                [InventoryController::class, 'update'])             ->name('inventory.update');
        Route::put('/inventory/{tool}/reorder-point',  [InventoryController::class, 'updateReorderPoint']) ->name('inventory.updateReorderPoint');
        Route::post('/inventory/{tool}/manages-stock', [InventoryController::class, 'toggleManagesStock']) ->name('inventory.toggleManagesStock');
    });

    // ユーザー管理（管理者のみ）
    Route::middleware('admin')->group(function () {
        Route::get('/users',             [UserController::class, 'index'])  ->name('users.index');
        Route::get('/users/create',      [UserController::class, 'create']) ->name('users.create');
        Route::post('/users',            [UserController::class, 'store'])  ->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])   ->name('users.edit');
        Route::put('/users/{user}',      [UserController::class, 'update']) ->name('users.update');
        Route::delete('/users/{user}',   [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 工具管理
    Route::get('/tools',                         [ToolController::class, 'index'])    ->name('tools.index');
    Route::get('/tools/category/{toolCategory}', [ToolController::class, 'category']) ->name('tools.category');
    Route::middleware('admin')->group(function () {
        Route::get('/tools/create',               [ToolController::class, 'create'])   ->name('tools.create');
        Route::post('/tools',                     [ToolController::class, 'store'])    ->name('tools.store');
        Route::get('/tools/{tool}/edit',          [ToolController::class, 'edit'])     ->name('tools.edit');
        Route::put('/tools/{tool}',               [ToolController::class, 'update'])   ->name('tools.update');
        Route::delete('/tools/{tool}',            [ToolController::class, 'destroy'])  ->name('tools.destroy');
        Route::post('/tools/{tool}/orders',         [OrderController::class, 'store'])   ->name('tools.orders.store');
        Route::post('/tools/{tool}/orders/receive', [OrderController::class, 'receive']) ->name('tools.orders.receive');
    });

    // 工具カテゴリー管理（管理者のみ）
    Route::middleware('admin')->group(function () {
        Route::get('/tool-categories/create',              [ToolCategoryController::class, 'create']) ->name('tool-categories.create');
        Route::post('/tool-categories',                    [ToolCategoryController::class, 'store'])  ->name('tool-categories.store');
        Route::get('/tool-categories/{toolCategory}/edit', [ToolCategoryController::class, 'edit'])   ->name('tool-categories.edit');
        Route::put('/tool-categories/{toolCategory}',      [ToolCategoryController::class, 'update']) ->name('tool-categories.update');
        Route::delete('/tool-categories/{toolCategory}',   [ToolCategoryController::class, 'destroy'])->name('tool-categories.destroy');
    });

    // マガジン管理
    Route::get('/magazines',                    [MagazineController::class, 'index'])  ->name('magazines.index');
    Route::get('/magazines/{machine}',          [MagazineController::class, 'show'])   ->name('magazines.show');
    Route::get('/magazines/{machine}/pots/{pot}', [MagazineController::class, 'showPot'])->name('magazines.showPot');
    Route::middleware('admin')->group(function () {
        Route::get('/magazines/{machine}/pots/create/{potNumber}', [MagazineController::class, 'createPot'])          ->name('magazines.createPot');
        Route::post('/magazines/{machine}/pots',                   [MagazineController::class, 'storePot'])            ->name('magazines.storePot');
        Route::get('/magazines/{machine}/pots/{pot}/edit',         [MagazineController::class, 'editPot'])             ->name('magazines.editPot');
        Route::put('/magazines/{machine}/pots/{pot}',              [MagazineController::class, 'updatePot'])           ->name('magazines.updatePot');
        Route::delete('/magazines/{machine}/pots/{pot}',           [MagazineController::class, 'destroyPot'])          ->name('magazines.destroyPot');
        Route::delete('/magazines/{machine}/pots/{pot}/with-tools',[MagazineController::class, 'destroyPotWithTools']) ->name('magazines.destroyPotWithTools');
        Route::post('/magazines/{machine}/pots/{potNumber}/disable',[MagazineController::class, 'disablePot'])         ->name('magazines.disablePot');
        Route::post('/magazines/{machine}/pots/{pot}/enable',      [MagazineController::class, 'enablePot'])           ->name('magazines.enablePot');
    });

    // 待機工具管理
    Route::get('/standby', [StandbyController::class, 'index'])->name('standby.index');
    Route::middleware('admin')->group(function () {
        Route::get('/standby/create',        [StandbyController::class, 'create'])  ->name('standby.create');
        Route::post('/standby',              [StandbyController::class, 'store'])   ->name('standby.store');
        Route::delete('/standby/{set}',      [StandbyController::class, 'destroy']) ->name('standby.destroy');
        Route::post('/standby/{set}/assign', [StandbyController::class, 'assign'])  ->name('standby.assign');
    });

    // 機械管理
    Route::get('/machines', [MachineController::class, 'index'])->name('machines.index');
    Route::middleware('admin')->group(function () {
        Route::get('/machines/create',         [MachineController::class, 'create']) ->name('machines.create');
        Route::post('/machines',               [MachineController::class, 'store'])  ->name('machines.store');
        Route::get('/machines/{machine}/edit', [MachineController::class, 'edit'])   ->name('machines.edit');
        Route::put('/machines/{machine}',      [MachineController::class, 'update']) ->name('machines.update');
        Route::delete('/machines/{machine}',   [MachineController::class, 'destroy'])->name('machines.destroy');
    });
});
