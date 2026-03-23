<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayrollPeriodController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeShiftController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PayslipAddController;
use App\Http\Controllers\PayrollItemSettingsController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\GovernmentContributionController;
use App\Http\Controllers\LoginController;

// login page
Route::get('/login', [LoginController::class, 'index']);

// login submit
Route::post('/login', [LoginController::class, 'login']);

// logout
Route::get('/logout', [LoginController::class, 'logout']);

/* ================= PAYROLL PERIOD ROUTES ================= */

Route::get('/payroll-period', [PayrollPeriodController::class, 'index']);
Route::get('/payroll-period/view/{id}', [PayrollPeriodController::class, 'view']);
Route::post('/payroll-period/store', [PayrollPeriodController::class, 'store']);
Route::post('/payroll-period/update/{id}', [PayrollPeriodController::class, 'update']);
Route::get('/payroll-period/delete/{id}', [PayrollPeriodController::class, 'delete']);
Route::post('/payroll-period/status/{id}', [PayrollPeriodController::class, 'changeStatus']);
Route::get('/payroll-calendar', [PayrollPeriodController::class, 'index'])->name('payroll.calendar');

/* ================= EMPLOYEE ROUTES ================= */

Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees/store', [EmployeeController::class, 'store']);
Route::get('/employees/delete/{id}', [EmployeeController::class, 'delete']);
Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit']);
Route::post('/employees/update/{id}', [EmployeeController::class, 'update']);
Route::post('/jobtitles/store',[EmployeeController::class,'storeJobTitle']);
Route::get('/jobtitles/delete/{id}',[EmployeeController::class,'deleteJobTitle']);

/* ================= DEPARTMENT ROUTES ================= */

Route::post('/departments/store', [EmployeeController::class, 'storeDepartment']);
Route::get('/departments/delete/{id}', [EmployeeController::class, 'deleteDepartment']);

/* ================= EMPLOYEE SHIFT ROUTES ================= */

Route::get('/employee-shifts', [EmployeeShiftController::class, 'index']);
Route::post('/employee-shifts/store', [EmployeeShiftController::class, 'store']);
Route::get('/employee-shifts/delete/{id}', [EmployeeShiftController::class, 'delete']);

/* ================= SHIFT ROUTES ================= */

Route::get('/shifts', [ShiftController::class, 'index']);
Route::post('/shifts/store', [ShiftController::class, 'store']);
Route::get('/shifts/delete/{id}', [ShiftController::class, 'delete']);
Route::get('/shifts/edit/{id}', [ShiftController::class, 'edit']);
Route::post('/shifts/update/{id}', [ShiftController::class, 'update']);

/* ================= PAYSLIP ROUTES ================= */

Route::get('/payslip', [PayslipController::class, 'index']);
Route::post('/payslip/update/{id}', [PayslipController::class, 'update']);
Route::post('/payslip/compute-attendance',[PayslipController::class,'computeAttendance']);
Route::get('/payslip/export/{id}', [PayslipController::class, 'export']);
Route::get('/payslip/export-period/{id}', [PayslipController::class,'exportPeriod']);
Route::delete('/payslip/delete/{id}', [PayslipController::class, 'destroy']);
Route::post('/payslip/bulk-action',[PayslipController::class,'bulkAction']);

/* ================= PAYSLIP ADD ROUTES ================= */

Route::get('/payslip/add', [PayslipAddController::class,'create']);
Route::post('/payslip/store', [PayslipAddController::class,'store']);
Route::post('/payslip/compute-statutory', [PayslipAddController::class,'compute']);
Route::post('/payslip/attendance-preview', [PayslipAddController::class, 'attendancePreview']);
Route::post('/payslip/attendance-payroll-preview',[ PayslipAddController::class,'attendancePayrollPreview']);
Route::post('/payslip/net-preview', [PayslipAddController::class,'netPreview']);

/* ================= PAYSLIP ITEMS SETTINGS ROUTES ================= */

Route::get('/payroll-items-settings', [PayrollItemSettingsController::class,'index'])->name('payroll.items.settings');
Route::post('/payroll-items-settings/update/{id}', [PayrollItemSettingsController::class,'update']);


/* ================= ATTENDANCE ROUTES ================= */

Route::get('/attendance-logs',[AttendanceLogController::class,'index']);
Route::post('/attendance-logs/store',[AttendanceLogController::class,'store']);


/* ================= GOVERNMENT ITEMS ROUTES ================= */

Route::get('/government-contributions', [GovernmentContributionController::class, 'index'])->name('gov.index');
Route::post('/sss/store', [GovernmentContributionController::class, 'storeSSS'])->name('sss.store');
Route::post('/sss/delete/{id}', [GovernmentContributionController::class, 'deleteSSS'])->name('sss.delete');
Route::post('/sss/update/{id}', [GovernmentContributionController::class, 'updateSSS'])->name('sss.update');
Route::post('/sss/toggle/{id}', [GovernmentContributionController::class,'toggleSSS'])->name('sss.toggle');

Route::post('/tax/store', [GovernmentContributionController::class, 'storeTax'])->name('tax.store');
Route::post('/tax/delete/{id}', [GovernmentContributionController::class, 'deleteTax'])->name('tax.delete');
Route::post('/tax/update/{id}', [GovernmentContributionController::class, 'updateTax'])->name('tax.update');
Route::post('/tax/toggle/{id}', [GovernmentContributionController::class,'toggleTax'])->name('tax.toggle');

Route::post('/philhealth/store',[GovernmentContributionController::class,'storePhilhealth'])->name('philhealth.store');
Route::post('/philhealth/update/{id}',[GovernmentContributionController::class,'updatePhilhealth'])->name('philhealth.update');
Route::post('/philhealth/delete/{id}',[GovernmentContributionController::class,'deletePhilhealth'])->name('philhealth.delete');
Route::post('/philhealth/toggle/{id}', [GovernmentContributionController::class,'togglePhilhealth'])->name('philhealth.toggle');

Route::post('/pagibig/store',[GovernmentContributionController::class,'storePagibig'])->name('pagibig.store');
Route::post('/pagibig/update/{id}',[GovernmentContributionController::class,'updatePagibig'])->name('pagibig.update');
Route::post('/pagibig/delete/{id}',[GovernmentContributionController::class,'deletePagibig'])->name('pagibig.delete');
Route::post('/pagibig/toggle/{id}', [GovernmentContributionController::class,'togglePagibig'])->name('pagibig.toggle');






