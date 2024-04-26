<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('inspections.index');
    })->name('index');

    Route::get('/dashboard', [
        App\Http\Controllers\DashboardController::class,
        'index'
    ])->name('dashboard');

    Route::get('/contact-us', App\Http\Livewire\ContactUs::class)->name('contact-us');

    // Agregar el middleware verify-free-trial al grupo
    Route::middleware(['auth.profile'])->group(function () {
        Route::prefix('finding-types')->group(function () {
            Route::get('/', App\Http\Livewire\FindingTypes\Index::class)->name('finding-types.index')
                ->middleware('verify-free-trial');
            Route::get('/form/{id?}', App\Http\Livewire\FindingTypes\Form::class)->name('finding-types.form')
                ->middleware('verify-free-trial');
            Route::get('/{file?}',)->name('finding-types.file')
                ->middleware('verify-free-trial');
        });
        Route::prefix('users')->group(function () {
            Route::get('/', App\Http\Livewire\Users\Index::class)->name('users.index')
                ->middleware('verify-free-trial');
            Route::get('/form/{id?}', App\Http\Livewire\Users\Form::class)->name('users.form')
                ->middleware('verify-free-trial');
            Route::get('/form/{id?}/add-credit', App\Http\Livewire\Users\AddCredit::class)->name('users.form.add-credit')
                ->middleware('verify-free-trial');
        });

        Route::prefix('companies')->group(function () {
            Route::get('/', App\Http\Livewire\Companies\Index::class)->name('companies.index')
                ->middleware('verify-free-trial');
            Route::get('/form/{id?}', App\Http\Livewire\Companies\Form::class)->name('companies.form')
                ->middleware('verify-free-trial');
        });
    });

    Route::prefix('inspections')->group(function () {
        Route::get('/', App\Http\Livewire\Operations\Index::class)->name('inspections.index');
        Route::get('/form/{id?}', App\Http\Livewire\Operations\Form::class)->name('inspections.form')
            ->middleware('verify-free-trial');
    });

    Route::prefix('billing')->group(function () {
        Route::get('/', [App\Http\Controllers\BillingController::class, 'index'])
            ->name('billing.index')
            ->middleware('verify-billing-access');
        Route::get('/{plan_code}', [App\Http\Controllers\BillingController::class, 'form'])
            ->name('billing.form')
            ->middleware('verify-billing-access');
        Route::post('/', [App\Http\Controllers\BillingController::class, 'checkout'])
            ->name('billing.checkout')
            ->middleware('verify-billing-access');
    });

    Route::get('/map', [
        App\Http\Controllers\MapController::class,
        'index'
    ])->name('map')
        ->middleware('verify-free-trial');

    Route::get('/map/image', function () {
        return redirect()->route('map')
            ->middleware('verify-free-trial');
    });

    Route::get('/map/image/{id}', App\Http\Livewire\Images\Form::class)
        ->name('map.image')
        ->middleware('verify-free-trial');

    Route::get('/map/report/{operationId?}', [
        App\Http\Controllers\MapController::class,
        'report'
    ])->name('map.report')
        ->middleware('verify-free-trial');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login/google', [App\Http\Controllers\GoogleController::class, 'login'])->name('login.google');
    Route::get('login/google/callback', [App\Http\Controllers\GoogleController::class, 'callback'])->name('login.google.callback');

    Route::get('login/linkedin', [App\Http\Controllers\LinkedinController::class, 'login'])->name('login.linkedin');
    Route::get('login/linkedin/callback', [App\Http\Controllers\LinkedinController::class, 'callback'])->name('login.lLinkedin.callback');
});
