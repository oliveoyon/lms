<?php

use App\Http\Controllers\Admin\AcademicController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ClassRoutineController;
use App\Http\Controllers\Admin\DependentController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FeeCollectionController;
use App\Http\Controllers\Admin\FeeSetupController;
use App\Http\Controllers\Admin\InvCategoryController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentManagement;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('tests', [TestController::class, 'test']);

Route::get('/', function () {
    return view('dashboard.admin.login1');
    // return redirect('/admin/login');
});

Route::get('admission-form', [GeneralController::class, 'admissionApply'])->name('admissionApply');
Route::post('/get-classes-by-version', [GeneralController::class, 'getClassesByVersion'])->name('getClassesByVersion');
Route::post('/stdApply', [GeneralController::class, 'stdApply'])->name('stdApply');
Route::get('/getslip/{std_hash_id}', [GeneralController::class, 'getslip'])->name('getslip');
Route::get('/getslip1/{std_hash_id}', [GeneralController::class, 'getslip1'])->name('getslip1');

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

        /*=================================
          Class Management
        =================================*/

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

        /*=================================
          Fee Management
        =================================*/

        // Fee Frequency Management
        Route::get('fee-frequency-list', [FeeSetupController::class, 'feeFrequencyList'])->name('fee-frequency-list');
        Route::post('addFeeFrequency', [FeeSetupController::class, 'addFeeFrequency'])->name('addFeeFrequency');
        Route::post('getFeeFrequencyDetails', [FeeSetupController::class, 'getFeeFrequencyDetails'])->name('getFeeFrequencyDetails');
        Route::post('updateFeeFrequencyDetails', [FeeSetupController::class, 'updateFeeFrequencyDetails'])->name('updateFeeFrequencyDetails');
        Route::post('deleteFeeFrequency', [FeeSetupController::class, 'deleteFeeFrequency'])->name('deleteFeeFrequency');
        Route::match(['get', 'post'], '/custom-fee-generate', [FeeSetupController::class, 'customFeeGen'])->name('customFeeGen');

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

        /*=================================
          Student Management
        =================================*/

        // Student Management
        Route::get('student-admission', [StudentManagement::class, 'admission'])->name('admission');
        Route::post('/stdAdmission', [StudentManagement::class, 'stdAdmission'])->name('stdAdmission');
        Route::get('bulk-student-admission', [StudentManagement::class, 'bulkadmission'])->name('bulkadmission');
        Route::post('/bulkstdAdmission', [StudentManagement::class, 'bulkstdAdmission'])->name('bulkstdAdmission');
        Route::get('student-list', [StudentManagement::class, 'stdlist'])->name('stdlist');
        Route::post('/getstdlist', [StudentManagement::class, 'getstdlist'])->name('getstdlist');
        Route::get('/data', [StudentManagement::class, 'data'])->name('data');
        Route::post('/stdEdit', [StudentManagement::class, 'stdEdit'])->name('stdEdit');
        Route::get('student-enroll', [StudentManagement::class, 'enroll'])->name('enroll');

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

        /*=================================
          Library Management
        =================================*/

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
        Route::post('/check-student-books', [LibraryController::class, 'checkStudentBooks'])->name('checkStudentBooks');
        Route::get('/suggestions', [LibraryController::class, 'suggestions'])->name('suggestions');
        Route::post('store-book-issues', [LibraryController::class, 'storeBookIssues'])->name('storeBookIssues');
        Route::get('get-student-list', [LibraryController::class, 'getStudentList'])->name('getStudentList');
        Route::get('/book-return', [LibraryController::class, 'book_return'])->name('book_return');

        /*=================================
          Event Management
        =================================*/

        // Event Controller
        Route::get('event-list', [EventController::class, 'eventList'])->name('event-list');
        Route::post('addEvent', [EventController::class, 'addEvent'])->name('addEvent');
        Route::post('getEventDetails', [EventController::class, 'getEventDetails'])->name('getEventDetails');
        Route::post('updateEventDetails', [EventController::class, 'updateEventDetails'])->name('updateEventDetails');
        Route::post('deleteEvent', [EventController::class, 'deleteEvent'])->name('deleteEvent');

        /*=================================
          Others Management
        =================================*/

        // Dependent Controller
        Route::post('/get-classes-by-version', [DependentController::class, 'getClassesByVersion'])->name('getClassesByVersion');
        Route::post('/get-sections-by-class', [DependentController::class, 'getSectionByClass'])->name('getSectionByClass');
        Route::post('/getSubjectsByClass', [DependentController::class, 'getSubjectsByClass'])->name('getSubjectsByClass');
        Route::post('/get-feegroup-by-ay', [DependentController::class, 'getFeegroupByAcademicYear'])->name('getFeegroupByAcademicYear');
        Route::post('/fetch-students-name', [DependentController::class, 'fetchStudentsName'])->name('fetchStudentsName');

        /*=================================
          Routine Management
        =================================*/

        // Class routine
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

        /*=================================
          Attendance Management
        =================================*/

        // Attendance Management
        Route::get('/attendance-input', [AttendanceController::class, 'attendanceInput'])->name('attendanceInput');
        Route::post('/fetch-students', [AttendanceController::class, 'fetchStudents'])->name('fetchStudents');
        Route::post('/addAttendance', [AttendanceController::class, 'addAttendance'])->name('addAttendance');
        Route::get('/edit-attendance', [AttendanceController::class, 'attendanceEdit'])->name('attendanceEdit');
        Route::post('/fetch-attendance-data', [AttendanceController::class, 'fetchAttendanceData'])->name('fetchAttendanceData');
        Route::post('/updateAttendance', [AttendanceController::class, 'updateAttendance'])->name('updateAttendance');

        Route::get('/student-profile/{std_hash_id}', [StudentManagement::class, 'studentProfile'])->name('studentProfile');
        Route::get('/edit-student/{std_hash_id}', [StudentManagement::class, 'editStudent'])->name('editStudent');
        Route::get('/applied-student-fullview/{std_hash_id}', [StudentManagement::class, 'fullView'])->name('fullView');
        Route::post('/stdAppliedEdit', [StudentManagement::class, 'stdAppliedEdit'])->name('stdAppliedEdit');

        Route::get('/getfeedet', [StudentManagement::class, 'getfeedet'])->name('getfeedet');

        /*=================================
          Settings
        =================================*/

        //Setting Management
        Route::get('/general-settings', [SettingController::class, 'genSetting'])->name('genSetting');
        Route::post('/edit-general-settings', [SettingController::class, 'editGenSetting'])->name('editGenSetting');

        Route::get('/collect-fees/{std_id?}', [FeeCollectionController::class, 'collectFee'])->name('collectFee');
        Route::post('/generate-bill', [FeeCollectionController::class, 'generateBill'])->name('generateBill');
        Route::post('/fetchColletcData', [FeeCollectionController::class, 'fetchColletcData'])->name('fetchColletcData');
        Route::post('/collectBill', [FeeCollectionController::class, 'collectBill'])->name('collectBill');

        /*=================================
          Inventory Management
        =================================*/

        //Inventory Management
        Route::get('category-list', [InvCategoryController::class, 'categorylist'])->name('category-list');
        Route::post('addCategory', [InvCategoryController::class, 'addCategory'])->name('addCategory');
        Route::post('getCategoryDetails', [InvCategoryController::class, 'getCategoryDetails'])->name('getCategoryDetails');
        Route::post('updateCategoryDetails', [InvCategoryController::class, 'updateCategoryDetails'])->name('updateCategoryDetails');
        Route::post('deleteCategory', [InvCategoryController::class, 'deleteCategory'])->name('deleteCategory');

        // Unit Management
        Route::get('unit-list', [UnitController::class, 'unitlist'])->name('unit-list');
        Route::post('addUnit', [UnitController::class, 'addUnit'])->name('addUnit');
        Route::post('getUnitDetails', [UnitController::class, 'getUnitDetails'])->name('getUnitDetails');
        Route::post('updateUnitDetails', [UnitController::class, 'updateUnitDetails'])->name('updateUnitDetails');
        Route::post('deleteUnit', [UnitController::class, 'deleteUnit'])->name('deleteUnit');

        // Store Management
        Route::get('store-list', [UnitController::class, 'storelist'])->name('store-list');
        Route::post('addStore', [UnitController::class, 'addStore'])->name('addStore');
        Route::post('getStoreDetails', [UnitController::class, 'getStoreDetails'])->name('getStoreDetails');
        Route::post('updateStoreDetails', [UnitController::class, 'updateStoreDetails'])->name('updateStoreDetails');
        Route::post('deleteStore', [UnitController::class, 'deleteStore'])->name('deleteStore');

        // Supplier Management
        Route::get('supplier-list', [UnitController::class, 'supplierlist'])->name('supplier-list');
        Route::post('addSupplier', [UnitController::class, 'addSupplier'])->name('addSupplier');
        Route::post('getSupplierDetails', [UnitController::class, 'getSupplierDetails'])->name('getSupplierDetails');
        Route::post('updateSupplierDetails', [UnitController::class, 'updateSupplierDetails'])->name('updateSupplierDetails');
        Route::post('deleteSupplier', [UnitController::class, 'deleteSupplier'])->name('deleteSupplier');

        /*=================================
          Transport Management
        =================================*/

        // Stopage Management
        Route::get('stopage-list', [TransportController::class, 'stopagelist'])->name('stopage-list');
        Route::post('addStopage', [TransportController::class, 'addStopage'])->name('addStopage');
        Route::post('getStopageDetails', [TransportController::class, 'getStopageDetails'])->name('getStopageDetails');
        Route::post('updateStopageDetails', [TransportController::class, 'updateStopageDetails'])->name('updateStopageDetails');
        Route::post('deleteStopage', [TransportController::class, 'deleteStopage'])->name('deleteStopage');

        Route::get('vehicletype-list', [TransportController::class, 'vehicletypelist'])->name('vehicletype-list');
        Route::post('addVehicletype', [TransportController::class, 'addVehicletype'])->name('addVehicletype');
        Route::post('getVehicletypeDetails', [TransportController::class, 'getVehicletypeDetails'])->name('getVehicletypeDetails');
        Route::post('updateVehicletypeDetails', [TransportController::class, 'updateVehicletypeDetails'])->name('updateVehicletypeDetails');
        Route::post('deleteVehicletype', [TransportController::class, 'deleteVehicletype'])->name('deleteVehicletype');

        // Vehicle Management
        Route::get('vehicle-list', [TransportController::class, 'vehiclelist'])->name('vehicle-list');
        Route::post('addVehicle', [TransportController::class, 'addVehicle'])->name('addVehicle');
        Route::post('getVehicleDetails', [TransportController::class, 'getVehicleDetails'])->name('getVehicleDetails');
        Route::post('updateVehicleDetails', [TransportController::class, 'updateVehicleDetails'])->name('updateVehicleDetails');
        Route::post('deleteVehicle', [TransportController::class, 'deleteVehicle'])->name('deleteVehicle');

        // Route Management
        Route::get('route-list', [TransportController::class, 'routelist'])->name('route-list');
        Route::post('addRoute', [TransportController::class, 'addRoute'])->name('addRoute');
        Route::post('getRouteDetails', [TransportController::class, 'getRouteDetails'])->name('getRouteDetails');
        Route::post('updateRouteDetails', [TransportController::class, 'updateRouteDetails'])->name('updateRouteDetails');
        Route::post('deleteRoute', [TransportController::class, 'deleteRoute'])->name('deleteRoute');
        Route::match(['get', 'post'], '/assign-students-transport', [TransportController::class, 'assignStdTrans'])->name('assignStdTrans');
        /*=================================
          Reports Management
        =================================*/

        // Reports
        Route::get('class-list-report', [ReportController::class, 'classlist_report'])->name('class-list-report');
        Route::get('version-wise-class-list-report', [ReportController::class, 'version_classlist_report'])->name('version-wise-class-list-report');
        Route::post('/versionWiseClassList', [ReportController::class, 'versionWiseClassList'])->name('versionWiseClassList');
        Route::get('version-wise-enrollment', [ReportController::class, 'version_enroll'])->name('version-wise-enrollment');
        Route::post('/versionWiseEnrollment', [ReportController::class, 'versionWiseEnrollment'])->name('versionWiseEnrollment');
        Route::match(['get', 'post'], '/class-summery', [ReportController::class, 'class_summery'])->name('class_summery');
        Route::match(['get', 'post'], '/class-stats', [ReportController::class, 'class_statistics'])->name('class_statistics');

        Route::match(['get', 'post'], '/class-wise-student-enrollment', [ReportController::class, 'class_student_count'])->name('class_student_count');
        Route::match(['get', 'post'], '/class-wise-subject-list', [ReportController::class, 'subject_list'])->name('subject_list');
        Route::match(['get', 'post'], '/class-wise-subject-count', [ReportController::class, 'subject_count'])->name('subject_count');
        Route::match(['get', 'post'], '/section-wise-teacher', [ReportController::class, 'section_wise_teacher'])->name('section_wise_teacher');
        Route::match(['get', 'post'], '/students-information', [ReportController::class, 'guardian_list'])->name('guardian_list');
        Route::match(['get', 'post'], '/class-wise-attendance', [ReportController::class, 'class_attendance'])->name('class_attendance');
        Route::get('fee-frequency-lists', [ReportController::class, 'FrequencyList'])->name('FrequencyList');
        Route::get('academic-fee-head-lists', [ReportController::class, 'FeeHeadList'])->name('FeeHeadList');
        Route::match(['get', 'post'], '/academic-fee-group-lists', [ReportController::class, 'FeeGroupList'])->name('FeeGroupList');
        Route::get('academic-fee-amount-lists', [ReportController::class, 'FeeAmountList'])->name('FeeAmountList');
        Route::match(['get', 'post'], '/due-fee-list', [ReportController::class, 'dueFeeList'])->name('dueFeeList');




        Route::post('/generate-pdf', [ReportController::class, 'generatePdf'])->name('generate-pdf');


    });
});
