<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SysLoginDataController;
use App\Http\Controllers\IOMPersonnelController;
use App\Http\Controllers\SocAdminsController;
use App\Http\Controllers\SysDashboardController;
use App\Http\Controllers\SysConfigsController;
use App\Http\Controllers\SysTrainingController;
use App\Http\Controllers\SysHealthInsuranceController;
use App\Http\Controllers\DataSystemDataController;

// System Config API
Route::get('/status/list', [SysConfigsController::class, 'list']);
Route::post('/status/change', [SysConfigsController::class, 'update']);

// Account API
Route::post('/account/login', [SysLoginDataController::class, 'login']);
Route::post('/account/reset', [SysLoginDataController::class, 'reset']);

// Dashboard
Route::get('/dashboard/empstats', [SysDashboardController::class, 'empstats']);
Route::get('/dashboard/lastteneval', [SysDashboardController::class, 'lastteneval']);
Route::get('/dashboard/lasttenleave', [SysDashboardController::class, 'lasttenleave']);
Route::get('/dashboard/leavestats', [SysDashboardController::class, 'leavestats']);


// SOC Admins API
Route::get('/admin/list', [SocAdminsController::class, 'list']);
Route::post('/admin/show', [SocAdminsController::class, 'show']);
Route::post('/admin/delete', [SocAdminsController::class, 'delete']);
Route::post('/admin/update', [SocAdminsController::class, 'update']);
Route::post('/admin/profileimage', [SocAdminsController::class, 'profileimage']);
Route::post('/admin/new', [SocAdminsController::class, 'new']);
Route::post('/admin/profile', [SocAdminsController::class, 'profile']);
Route::post('/admin/loginifo', [SocAdminsController::class, 'loginifo']);
Route::post('/admin/account', [SocAdminsController::class, 'account']);

// IOM HR API
Route::get('/hr/list', [IomPersonnelController::class, 'list']);
Route::post('/hr/show', [IomPersonnelController::class, 'show']);
Route::post('/hr/delete', [IomPersonnelController::class, 'delete']);
Route::post('/hr/update', [IomPersonnelController::class, 'update']);
Route::post('/hr/profileimage', [IomPersonnelController::class, 'profileimage']);
Route::post('/hr/new', [IomPersonnelController::class, 'new']);
Route::post('/hr/profile', [IomPersonnelController::class, 'profile']);
Route::post('/hr/loginifo', [IomPersonnelController::class, 'loginifo']);
Route::post('/hr/account', [IomPersonnelController::class, 'account']);




// Health Insurance API
Route::get('/healthinsurance/files', [SysHealthInsuranceController::class, 'listfiles']);
Route::get('/healthinsurance/info', [SysHealthInsuranceController::class, 'listinfo']);
Route::post('/healthinsurance/showfile', [SysHealthInsuranceController::class, 'showfile']);
Route::post('/healthinsurance/changefile', [SysHealthInsuranceController::class, 'editfile']);
Route::post('/healthinsurance/editinfo', [SysHealthInsuranceController::class, 'editinfo']);
Route::post('/healthinsurance/deletefile', [SysHealthInsuranceController::class, 'deletefile']);
Route::post('/healthinsurance/addfile', [SysHealthInsuranceController::class, 'addfile']);

// Mandatory Training API
Route::get('/training/list', [SysTrainingController::class, 'list']);
Route::post('/training/show', [SysTrainingController::class, 'show']);
Route::post('/training/delete', [SysTrainingController::class, 'delete']);
Route::post('/training/update', [SysTrainingController::class, 'update']);
Route::post('/training/new', [SysTrainingController::class, 'new']);

// System Data API
Route::get('/data/contractcategories', [DataSystemDataController::class, 'contractcategories']);
Route::get('/data/contracttype', [DataSystemDataController::class, 'contracttype']);
Route::get('/data/dangerpay', [DataSystemDataController::class, 'dangerpay']);
Route::get('/data/divison', [DataSystemDataController::class, 'divison']);
Route::get('/data/dutystation', [DataSystemDataController::class, 'dutystation']);
Route::get('/data/gender', [DataSystemDataController::class, 'gender']);
Route::get('/data/grade', [DataSystemDataController::class, 'grade']);
Route::get('/data/hub', [DataSystemDataController::class, 'hub']);
Route::get('/data/leavetype', [DataSystemDataController::class, 'leavetype']);
Route::get('/data/maritalstatus', [DataSystemDataController::class, 'maritalstatus']);
Route::get('/data/paymenttype', [DataSystemDataController::class, 'paymenttype']);
Route::get('/data/programme', [DataSystemDataController::class, 'programme']);
Route::get('/data/staffgrouping', [DataSystemDataController::class, 'staffgrouping']);
Route::get('/data/yesno', [DataSystemDataController::class, 'yesno']);