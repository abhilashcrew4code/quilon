<?php


use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    if (Auth::user()) {
        return redirect()->route('home');
    } else {
        return view('auth.login');
    }
});


Route::post('/login', [CustomAuthController::class, 'authenticate'])->name('login');
Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');


Route::get('demo', function () {
    return view('demo');
});


Route::middleware([
    'auth',
    'UserLog',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::impersonate();

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/profit-calculation', [CalculationController::class, 'profitCalculation'])->name('profit-calculation');


    Route::resource('users', UserController::class);
    Route::resource('expenses', ExpenseController::class);

    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');

    Route::resource('enquiry', EnquiryController::class);

    Route::get('/enquiry/delete/{id}', [EnquiryController::class, 'deleteData'])->name('enquiry.delete');
    Route::post('/enquiry/delete/{id}', [EnquiryController::class, 'deleteData'])->name('enquiry.delete');

    Route::get('dashboard', [DashboardController::class, 'getDashboard'])->name('dashboard');

    Route::get('overview-dashboard', [DashboardController::class, 'overviewDashboard'])->name('overview-dashboard');
    Route::get('chart-dashboard', [DashboardController::class, 'chartDashboard'])->name('chart-dashboard');


    Route::get('dashboard/product-data', [DashboardController::class, 'productData'])
        ->name('dashboard.product.data');

    Route::get('dashboard/last-30-days-sales', [DashboardController::class, 'last30DaysSales'])
        ->name('dashboard.last30days.sales');

    Route::get('dashboard/financial-overview', [DashboardController::class, 'financialOverview'])
        ->name('dashboard.financial.overview');

    Route::get('dashboard/last-30-days-income', [DashboardController::class, 'last30DaysIncome'])
        ->name('dashboard.last30days.income');

    Route::resource('announcements', AnnouncementController::class);
    Route::get('/announcements/delete/{id}', [AnnouncementController::class, 'deleteData'])->name('announcements.delete');
    Route::post('/announcements/delete/{id}', [AnnouncementController::class, 'deleteData'])->name('announcements.delete');

    Route::resource('settings', SettingsController::class);

    Route::resource('roles', RoleController::class);

    Route::get('roles/assign_permission/{id}', [RoleController::class, 'getAssignPermissionOld'])->name('roles.assign_permission.get');
    Route::post('roles/assign_permission/{id}', [RoleController::class, 'assignPermissionOld'])->name('roles.assign_permission');

    Route::get('roles/assign/permissions/{id}', [RoleController::class, 'getAssignRolePermission'])->name('acl.role.assign.permissions');
    Route::post('roles/assign/permissions/{id}', [RoleController::class, 'postAssignRolePermission'])->name('acl.role.assign.permissions');

    Route::get('user/permissions', [RoleController::class, 'getAssignUserPermissionList'])->name('acl.user.permissions.view');
    Route::post('user/permissions', [RoleController::class, 'getAssignUserPermissionList'])->name('acl.user.permissions.view');

    Route::get('user/assign/permissions/{id}', [RoleController::class, 'getAssignUserPermission'])->name('acl.user.assign.permissions');
    Route::post('user/assign/permissions/{id}', [RoleController::class, 'postAssignUserPermission'])->name('acl.user.assign.permissions');

    Route::get('/user/impersonate/start/{id}', [UserController::class, 'startImpersonate'])->name('user.impersonate.start')->middleware(['permission:users.list']);
    Route::get('/user/impersonate/stop/', [UserController::class, 'stopImpersonate'])->name('user.impersonate.stop');

    Route::get('change/password/{id}', [UserController::class, 'getChangePassword'])->name('change.password.get');
    Route::post('change/password/{id}', [UserController::class, 'changePassword'])->name('change.password');

    Route::get('/manage/portal/access/{id}', [UserController::class, 'managePortalAccess'])->name('manage.portal.access');
    Route::post('/manage/portal/access/{id}', [UserController::class, 'managePortalAccess'])->name('manage.portal.access');

    Route::resource('permissions', PermissionController::class);
    Route::prefix('permission_groups')->group(function () {
        Route::get('/create', [PermissionController::class, 'create_permission_group'])->name('permission_groups.create');
        Route::post('/create', [PermissionController::class, 'store_permission_group'])->name('permission_groups.store');
        Route::get('/edit/{id}', [PermissionController::class, 'edit_permission_group'])->name('permission_groups.edit');
        Route::post('/edit/{id}', [PermissionController::class, 'update_permission_group'])->name('permission_groups.update');
    });

    Route::get('change-password', [UserController::class, 'passwordChange'])->name('password-change');
    Route::post('change-password', [UserController::class, 'passwordUpdate'])->name('password-update');

    Route::get('user-profile', [UserController::class, 'userProfile'])->name('user-profile');
    Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update-profile');

    Route::prefix('reports')->group(function () {

        Route::get('/login/logs', [ReportController::class, 'viewLoginLogs'])->name('reports.login.logs.list')->middleware(['permission:reports.login.logs.list']);
        Route::post('/login/logs', [ReportController::class, 'viewLoginLogs'])->name('reports.login.logs.list')->middleware(['permission:reports.login.logs.list']);
    });
});


Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:clear');
    return 'Cleared';
});

Route::get('/run-artisan/{command}', function ($command) {
    Artisan::call($command);
    return Artisan::output();
});
