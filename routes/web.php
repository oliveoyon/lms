<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
    // return redirect('/admin/login');
});

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('user')->name('user.')->group(function () {

    Route::middleware(['guest:web', 'PreventBackHistory'])->group(function () {

        Route::view('/login', 'dashboard.user.login')->name('login');
        Route::view('/register', 'dashboard.user.register')->name('register');
        Route::post('create', [UserController::class, 'create'])->name('create');
        Route::post('check', [UserController::class, 'check'])->name('check');
    });

    Route::middleware(['auth:web', 'PreventBackHistory', 'is_first_login'])->group(function () {
        Route::view('/home', 'dashboard.user.home')->name('home');
    });

    Route::middleware(['auth:web', 'PreventBackHistory'])->group(function () {
        Route::view('/change-password', 'dashboard.user.changepass')->name('changepass');
        Route::post('/change-passowrd-action', [UserController::class, 'changePassword'])->name('changepassaction');
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
    });
});



Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['guest:admin', 'PreventBackHistory'])->group(function () {
        Route::view('/login', 'dashboard.admin.login1')->name('login');
        Route::post('check', [AdminController::class, 'check'])->name('check');
    });

    Route::middleware(['auth:admin', 'PreventBackHistory'])->group(function () {
        Route::view('/change-password', 'dashboard.admin.changepass')->name('changepass');
        Route::post('/change-passowrd-action', [AdminController::class, 'changePassword'])->name('changepassaction');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth:admin', 'PreventBackHistory', 'is_admin_first_login'])->group(function () {
        Route::get('home', [AdminController::class, 'index'])->name('home');
        
    });
});
