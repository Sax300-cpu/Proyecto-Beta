<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReembolsoController;
use App\Http\Middleware\CheckBusNoPartido;
use Illuminate\Support\Facades\Route;

// ─── Públicas ────────────────────────────────────────────────────────────────
Route::view('/', 'home')->name('home');

// ─── Autenticadas ─────────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Historial de boletos del usuario
    Route::get('/mis-boletos', [BoletoController::class, 'index'])->name('mis-boletos');

    // Compra de boleto (con restricción de bus no partido para oficinistas)
    Route::get('/boleto/comprar/{hojaRuta}', [BoletoController::class, 'comprar'])
        ->middleware(CheckBusNoPartido::class)
        ->name('boleto.comprar');

    // Ver boleto (solo el propietario o staff)
    Route::get('/boleto/{boleto}', [BoletoController::class, 'ver'])->name('boleto.ver');
    Route::post('/boleto/{boleto}/reembolso', [ReembolsoController::class, 'store'])->name('boleto.reembolso.store');

    // Checkout PayPal Sandbox para usuarios finales
    Route::post('/checkout/paypal/{boleto}/create', [CheckoutController::class, 'create'])->name('checkout.paypal.create');
    Route::get('/checkout/paypal/{boleto}/success', [CheckoutController::class, 'success'])->name('checkout.paypal.success');
    Route::get('/checkout/paypal/{boleto}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.paypal.cancel');

    // Perfil de usuario (Agregado por Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Admin y Oficinista ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Admin|Oficinista'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Buses
    Route::resource('buses', \App\Http\Controllers\Admin\BusController::class)->except(['show']);

    // CRUD Rutas
    Route::resource('rutas', \App\Http\Controllers\Admin\RutaController::class)->except(['show']);

    // CRUD Frecuencias
    Route::resource('frecuencias', \App\Http\Controllers\Admin\FrecuenciaController::class)->except(['show']);

    // Hojas de Ruta
    Route::get('/hojas-ruta', [AdminController::class, 'hojasRuta'])->name('hojas-ruta.index');
    Route::post('/hojas-ruta', [AdminController::class, 'storeHojaRuta'])->name('hojas-ruta.store');
    Route::patch('/hojas-ruta/{hojaRuta}/estado', [AdminController::class, 'cambiarEstadoHojaRuta'])->name('hojas-ruta.estado');
    Route::patch('/hojas-ruta/{hojaRuta}/cambiar-bus', [AdminController::class, 'cambiarBusHojaRuta'])->name('hojas-ruta.cambiar-bus');

    // Validación de pagos (Livewire)
    Route::get('/pagos', function () {
        return view('admin.pagos');
    })->name('pagos');

    // Gestión de Usuarios
    Route::resource('usuarios', \App\Http\Controllers\Admin\UserController::class)->except(['show']);

});

// ─── Solo Admin ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/cooperativa', [AdminController::class, 'cooperativa'])->name('cooperativa');
    Route::patch('/cooperativa', [AdminController::class, 'updateCooperativa'])->name('cooperativa.update');
    Route::resource('categorias-bus', \App\Http\Controllers\Admin\CategoriaBusController::class)->except(['show']);

    // CRUD de Paradas (Administrador)
    Route::resource('paradas', \App\Http\Controllers\Admin\ParadaController::class)->except(['show']);

    // Reportes (Administrador)
    Route::get('reportes', [\App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('reportes.index');

});

// ─── Chofer ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Chofer'])->prefix('chofer')->name('chofer.')->group(function () {
    Route::get('/escaner', [ChoferController::class, 'escaner'])->name('escaner');
    Route::get('/validar/{qrCode}', [ChoferController::class, 'validarQr'])->name('validar-qr');
    Route::post('/validar/{boleto}', [ChoferController::class, 'accionQr'])->name('validar-qr.accion');
    Route::get('/vender-en-ruta/{hojaRuta}', [ChoferController::class, 'venderEnRuta'])->name('vender-en-ruta');
    Route::post('/vender-en-ruta/{hojaRuta}', [ChoferController::class, 'storeVentaEnRuta'])->name('vender-en-ruta.store');

});

// ─── Auth (Breeze) ────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';
