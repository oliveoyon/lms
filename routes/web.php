<?php

use App\Http\Controllers\Admin\AcademicController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ClassRoutineController;
use App\Http\Controllers\Admin\DependentController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FeeSetupController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\StudentManagement;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
    // return redirect('/admin/login');
});

Route::get('/migrate-and-seed', function () {
    // Run migration
    Artisan::call('migrate');

    // Run DatabaseSeeder seeder
    Artisan::call('db:seed --class=DatabaseSeeder');

    return 'Database migrated and seeded successfully.';
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

        // Subject Management
        Route::get('subject-list', [AcademicController::class, 'subjectList'])->name('subject-list');
        Route::post('addSubject', [AcademicController::class, 'addSubject'])->name('addSubject');
        Route::post('getSubjectDetails', [AcademicController::class, 'getSubjectDetails'])->name('getSubjectDetails');
        Route::post('updateSubjectDetails', [AcademicController::class, 'updateSubjectDetails'])->name('updateSubjectDetails');
        Route::post('deleteSubject', [AcademicController::class, 'deleteSubject'])->name('deleteSubject');

        // Fee Frequency Management
        Route::get('fee-frequency-list', [FeeSetupController::class, 'feeFrequencyList'])->name('fee-frequency-list');
        Route::post('addFeeFrequency', [FeeSetupController::class, 'addFeeFrequency'])->name('addFeeFrequency');
        Route::post('getFeeFrequencyDetails', [FeeSetupController::class, 'getFeeFrequencyDetails'])->name('getFeeFrequencyDetails');
        Route::post('updateFeeFrequencyDetails', [FeeSetupController::class, 'updateFeeFrequencyDetails'])->name('updateFeeFrequencyDetails');
        Route::post('deleteFeeFrequency', [FeeSetupController::class, 'deleteFeeFrequency'])->name('deleteFeeFrequency');

        // Academic Fee Head Management
        Route::get('academic-fee-head-list', [FeeSetupController::class, 'academicFeeHeadList'])->name('academic-fee-head-list');
        Route::post('addAcademicFeeHead', [FeeSetupController::class, 'addAcademicFeeHead'])->name('addAcademicFeeHead');
        Route::post('getAcademicFeeHeadDetails', [FeeSetupController::class, 'getAcademicFeeHeadDetails'])->name('getAcademicFeeHeadDetails');
        Route::post('updateAcademicFeeHeadDetails', [FeeSetupController::class, 'updateAcademicFeeHeadDetails'])->name('updateAcademicFeeHeadDetails');
        Route::post('deleteAcademicFeeHead', [FeeSetupController::class, 'deleteAcademicFeeHead'])->name('deleteAcademicFeeHead');

        // Academic Fee Group Management
        Route::get('academic-fee-group-list', [FeeSetupController::class, 'academicFeeGroupList'])->name('academic-fee-group-list');
        Route::post('addAcademicFeeGroup', [FeeSetupController::class, 'addAcademicFeeGroup'])->name('addAcademicFeeGroup');
        Route::post('getAcademicFeeGroupDetails', [FeeSetupController::class, 'getAcademicFeeGroupDetails'])->name('getAcademicFeeGroupDetails');
        Route::post('updateAcademicFeeGroupDetails', [FeeSetupController::class, 'updateAcademicFeeGroupDetails'])->name('updateAcademicFeeGroupDetails');
        Route::post('deleteAcademicFeeGroup', [FeeSetupController::class, 'deleteAcademicFeeGroup'])->name('deleteAcademicFeeGroup');


        // Routes for Academic Fee Amount Management
        Route::get('academic-fee-amount-list', [FeeSetupController::class, 'academicFeeAmountList'])->name('academic-fee-amount-list');
        Route::post('addAcademicFeeAmount', [FeeSetupController::class, 'addAcademicFeeAmount'])->name('addAcademicFeeAmount');
        Route::post('getAcademicFeeAmountDetails', [FeeSetupController::class, 'getAcademicFeeAmountDetails'])->name('getAcademicFeeAmountDetails');
        Route::post('updateAcademicFeeAmountDetails', [FeeSetupController::class, 'updateAcademicFeeAmountDetails'])->name('updateAcademicFeeAmountDetails');
        Route::post('deleteAcademicFeeAmount', [FeeSetupController::class, 'deleteAcademicFeeAmount'])->name('deleteAcademicFeeAmount');
        Route::get('/get-fee-heads', [FeeSetupController::class, 'getFeeHeads'])->name('get-fee-heads');
        Route::get('/get-group-data', [FeeSetupController::class, 'getGroupData'])->name('getGroupData');

        // Student Management
        Route::get('student-admission', [StudentManagement::class, 'admission'])->name('admission');
        Route::post('/stdAdmission', [StudentManagement::class, 'stdAdmission'])->name('stdAdmission');
        Route::get('bulk-student-admission', [StudentManagement::class, 'bulkadmission'])->name('bulkadmission');
        Route::post('/bulkstdAdmission', [StudentManagement::class, 'bulkstdAdmission'])->name('bulkstdAdmission');
        Route::get('student-list', [StudentManagement::class, 'stdlist'])->name('stdlist');
        Route::post('/getstdlist', [StudentManagement::class, 'getstdlist'])->name('getstdlist');
        Route::get('/data', [StudentManagement::class, 'data'])->name('data');
        Route::post('/stdEdit', [StudentManagement::class, 'stdEdit'])->name('stdEdit');

        // Teacher Management
        Route::get('teacher-list', [TeacherController::class, 'teacherlist'])->name('teacher-list');
        Route::post('addTeacher', [TeacherController::class, 'addTeacher'])->name('addTeacher');
        Route::post('getTeacherDetails', [TeacherController::class, 'getTeacherDetails'])->name('getTeacherDetails');
        Route::post('updateTeacherDetails', [TeacherController::class, 'updateTeacherDetails'])->name('updateTeacherDetails');
        Route::post('deleteTeacher', [TeacherController::class, 'deleteTeacher'])->name('deleteTeacher');

        // Assigned Teacher Management
        Route::get('assigned-teacher-list', [TeacherController::class, 'assignTeacherList'])->name('assigned-teacher-list');
        Route::post('addAssignedTeacher', [TeacherController::class, 'addAssignedTeacher'])->name('addAssignedTeacher');
        Route::post('getAssignedTeacherDetails', [TeacherController::class, 'getAssignedTeacherDetails'])->name('getAssignedTeacherDetails');
        Route::post('updateAssignedTeacher', [TeacherController::class, 'updateAssignedTeacher'])->name('updateAssignedTeacher');
        Route::post('deleteAssignedTeacher', [TeacherController::class, 'deleteAssignedTeacher'])->name('deleteAssignedTeacher');


        // Book Category Management
        Route::get('book-category-list', [LibraryController::class, 'bookCategoryList'])->name('book-category-list');
        Route::post('addBookCategory', [LibraryController::class, 'addBookCategory'])->name('addBookCategory');
        Route::post('getBookCategoryDetails', [LibraryController::class, 'getBookCategoryDetails'])->name('getBookCategoryDetails');
        Route::post('updateBookCategoryDetails', [LibraryController::class, 'updateBookCategoryDetails'])->name('updateBookCategoryDetails');
        Route::post('deleteBookCategory', [LibraryController::class, 'deleteBookCategory'])->name('deleteBookCategory');

        Route::get('book-list', [LibraryController::class, 'bookList'])->name('book-list');
        Route::post('addBook', [LibraryController::class, 'addBook'])->name('addBook');
        Route::post('getBookDetails', [LibraryController::class, 'getBookDetails'])->name('getBookDetails');
        Route::post('updateBookDetails', [LibraryController::class, 'updateBookDetails'])->name('updateBookDetails');
        Route::post('deleteBook', [LibraryController::class, 'deleteBook'])->name('deleteBook');
        Route::get('/book-issue', [LibraryController::class, 'book_issue'])->name('book_issue');
        Route::post('/check-book-details', [LibraryController::class, 'checkBookDetails']);
        Route::post('/check-student-books', [LibraryController::class, 'checkStudentBooks'])->name('checkStudentBooks');
        Route::get('/suggestions', [LibraryController::class, 'suggestions'])->name('suggestions');
        Route::post('store-book-issues', [LibraryController::class, 'storeBookIssues'])->name('storeBookIssues');
        Route::get('get-student-list', [LibraryController::class, 'getStudentList'])->name('getStudentList');

        // Event Controller
        Route::get('event-list', [EventController::class, 'eventList'])->name('event-list');
        Route::post('addEvent', [EventController::class, 'addEvent'])->name('addEvent');
        Route::post('getEventDetails', [EventController::class, 'getEventDetails'])->name('getEventDetails');
        Route::post('updateEventDetails', [EventController::class, 'updateEventDetails'])->name('updateEventDetails');
        Route::post('deleteEvent', [EventController::class, 'deleteEvent'])->name('deleteEvent');

        // Dependent Controller
        Route::post('/get-classes-by-version', [DependentController::class, 'getClassesByVersion'])->name('getClassesByVersion');
        Route::post('/get-sections-by-class', [DependentController::class, 'getSectionByClass'])->name('getSectionByClass');
        Route::post('/getSubjectsByClass', [DependentController::class, 'getSubjectsByClass'])->name('getSubjectsByClass');
        Route::post('/get-feegroup-by-ay', [DependentController::class, 'getFeegroupByAcademicYear'])->name('getFeegroupByAcademicYear');


        // View to display the form for creating a new class routine
        Route::get('/create-class-periods', [ClassRoutineController::class, 'createClassRoutine'])->name('createClassRoutine');
        Route::post('/addPeriods', [ClassRoutineController::class, 'addPeriods'])->name('addPeriods');
        Route::get('/show-class-periods', [ClassRoutineController::class, 'showClassRoutine'])->name('showClassRoutine');
        Route::post('/getPeriods', [ClassRoutineController::class, 'getPeriods'])->name('getPeriods');
        Route::post('/updateClassPeriodDetails', [ClassRoutineController::class, 'updateClassPeriodDetails'])->name('updateClassPeriodDetails');
        Route::get('/create-class-routine', [ClassRoutineController::class, 'createRoutine'])->name('createRoutine');
        Route::post('/getRoutineData', [ClassRoutineController::class, 'getRoutineData'])->name('getRoutineData');
        Route::post('/addRoutine', [ClassRoutineController::class, 'addRoutine'])->name('addRoutine');
        Route::get('/testing', [ClassRoutineController::class, 'testing'])->name('testing');
        Route::get('/showRoutine1', [ClassRoutineController::class, 'showRoutine1'])->name('showRoutine1');
        Route::post('/fetchRoutine', [ClassRoutineController::class, 'fetchRoutine'])->name('fetchRoutine');

        // Attendance Management
        Route::get('/attendance-input', [AttendanceController::class, 'attendanceInput'])->name('attendanceInput');
        Route::post('/fetch-students', [AttendanceController::class, 'fetchStudents'])->name('fetchStudents');
        Route::post('/addAttendance', [AttendanceController::class, 'addAttendance'])->name('addAttendance');
        Route::get('/edit-attendance', [AttendanceController::class, 'attendanceEdit'])->name('attendanceEdit');
        Route::post('/fetch-attendance-data', [AttendanceController::class, 'fetchAttendanceData'])->name('fetchAttendanceData');
        Route::post('/updateAttendance', [AttendanceController::class, 'updateAttendance'])->name('updateAttendance');

        Route::get('/student-profile/{std_hash_id}', [StudentManagement::class, 'studentProfile'])->name('studentProfile');
        Route::get('/edit-student/{std_hash_id}', [StudentManagement::class, 'editStudent'])->name('editStudent');


        Route::get('/getfeedet', [StudentManagement::class, 'getfeedet'])->name('getfeedet');
    });
});
