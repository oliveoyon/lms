<?php

use App\Http\Controllers\Admin\AcademicController;
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
        
        // Version Management
        Route::get('version-list', [AcademicController::class, 'versionlist'])->name('version-list');
        Route::post('addVersion', [AcademicController::class, 'addVersion'])->name('addVersion');
        Route::post('getVersionDetails', [AcademicController::class, 'getVersionDetails'])->name('getVersionDetails');
        Route::post('updateVersionDetails', [AcademicController::class, 'updateVersionDetails'])->name('updateVersionDetails');
        Route::post('deleteVersion', [AcademicController::class, 'deleteVersion'])->name('deleteVersion');
        
        // Class Management
        Route::get('class-list', [AcademicController::class, 'classlist'])->name('class-list');
        Route::post('addClass', [AcademicController::class, 'addClass'])->name('addClass');
        Route::post('getClassDetails', [AcademicController::class, 'getClassDetails'])->name('getClassDetails');
        Route::post('updateClassDetails', [AcademicController::class, 'updateClassDetails'])->name('updateClassDetails');
        Route::post('deleteClass', [AcademicController::class, 'deleteClass'])->name('deleteClass');

        // Section Management
        Route::get('section-list', [AcademicController::class, 'sectionlist'])->name('section-list');
        Route::post('addSection', [AcademicController::class, 'addSection'])->name('addSection');
        Route::post('getSectionDetails', [AcademicController::class, 'getSectionDetails'])->name('getSectionDetails');
        Route::post('updateSectionDetails', [AcademicController::class, 'updateSectionDetails'])->name('updateSectionDetails');
        Route::post('deleteSection', [AcademicController::class, 'deleteSection'])->name('deleteSection');
        Route::post('/get-classes-by-version', [AcademicController::class, 'getClassesByVersion'])->name('getClassesByVersion');

        // Subject Management
        Route::get('subject-list', [AcademicController::class, 'subjectList'])->name('subject-list');
        Route::post('addSubject', [AcademicController::class, 'addSubject'])->name('addSubject');
        Route::post('getSubjectDetails', [AcademicController::class, 'getSubjectDetails'])->name('getSubjectDetails');
        Route::post('updateSubjectDetails', [AcademicController::class, 'updateSubjectDetails'])->name('updateSubjectDetails');
        Route::post('deleteSubject', [AcademicController::class, 'deleteSubject'])->name('deleteSubject');

    });
});
