<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('listings', ListingController::class);
    Route::get('/my-contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts/store-contract', [ContractController::class, 'createContract'])->name('contracts.store-contract');
    Route::resource('contracts', ContractController::class)->except(['index', 'create']);
    Route::get('/contracts/{contract}/download', [ContractController::class, 'download'])->name('contracts.download');
    Route::post('/contracts/{contract}/cancel', [ContractController::class, 'cancel'])->name('contracts.cancel');
    Route::post('/contracts/{contract}/negotiate', [ContractController::class, 'negotiate'])->name('contracts.negotiate');
    Route::post('/contracts/{contract}/sign', [ContractController::class, 'sign'])->name('contracts.sign');
    Route::post('/contracts/{contract}/complete', [ContractController::class, 'markCompleted'])->name('contracts.complete');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    
    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{contract}/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/contracts/{contract}/pay', [PaymentController::class, 'store'])->name('payments.store');
    
    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{contract}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{contract}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/download/{message}', [MessageController::class, 'downloadAttachment'])->name('messages.download');
    
    // Farmer Contract Routes
    Route::get('/farmer/contract-requests', [ContractController::class, 'farmerRequests'])->name('farmer.contract-requests');
    Route::get('/farmer/my-contracts', [ContractController::class, 'farmerContracts'])->name('farmer.my-contracts');
    Route::post('/farmer/contracts/{contract}/accept', [ContractController::class, 'accept'])->name('farmer.contract-accept');
    Route::post('/farmer/contracts/{contract}/reject', [ContractController::class, 'reject'])->name('farmer.contract-reject');
    Route::post('/farmer/contracts/{contract}/modify', [ContractController::class, 'requestModification'])->name('farmer.contract-modify');
    Route::post('/farmer/contracts/{contract}/update-delivery', [ContractController::class, 'updateDeliveryStatus'])->name('farmer.update-delivery');
    
    // Farmer Orders Routes
    Route::get('/farmer/orders', [OrderController::class, 'farmerOrders'])->name('farmer.orders');
    Route::get('/farmer/orders/{order}', [OrderController::class, 'farmerOrderShow'])->name('farmer.order-show');
    
    // Farmer Payments Routes
    Route::get('/farmer/payments', [PaymentController::class, 'farmerPayments'])->name('farmer.payments');
    Route::post('/farmer/payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    
    // Farmer Messages Routes
    Route::get('/farmer/messages', [MessageController::class, 'farmerIndex'])->name('farmer.messages.index');
    Route::get('/farmer/messages/{contract}', [MessageController::class, 'farmerShow'])->name('farmer.messages.show');
    Route::post('/farmer/messages/{contract}', [MessageController::class, 'farmerStore'])->name('farmer.messages.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
