<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SysLoginDataController;
use App\Http\Controllers\IOMPersonnelController;
use App\Http\Controllers\SocAdminsController;
// Account API
Route::post('/account/login', [SysLoginDataController::class, 'login']);
Route::post('/account/reset', [SysLoginDataController::class, 'reset']);

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

// SOC Admins API
Route::get('/hr/list', [IomPersonnelController::class, 'list']);
Route::post('/hr/show', [IomPersonnelController::class, 'show']);
Route::post('/hr/delete', [IomPersonnelController::class, 'delete']);
Route::post('/hr/update', [IomPersonnelController::class, 'update']);
Route::post('/hr/profileimage', [IomPersonnelController::class, 'profileimage']);
Route::post('/hr/new', [IomPersonnelController::class, 'new']);
Route::post('/hr/profile', [IomPersonnelController::class, 'profile']);
Route::post('/hr/loginifo', [IomPersonnelController::class, 'loginifo']);
Route::post('/hr/account', [IomPersonnelController::class, 'account']);