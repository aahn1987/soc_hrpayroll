<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SysLoginDataController;
use App\Http\Controllers\IOMPersonnelController;
Route::post('/user/login', [SysLoginDataController::class, 'login']);
Route::post('/user/reset', [SysLoginDataController::class, 'reset']);


Route::get('/iom/list', [IomPersonnelController::class, 'list']);
Route::post('/iom/show', [IomPersonnelController::class, 'show']);
Route::post('/iom/delete', [IomPersonnelController::class, 'delete']);
Route::post('/iom/update', [IomPersonnelController::class, 'update']);
Route::post('/iom/profileimage', [IomPersonnelController::class, 'profileimage']);
Route::post('/iom/new', [IomPersonnelController::class, 'new']);
Route::post('/iom/profile', [IomPersonnelController::class, 'editprofile']);
Route::post('/iom/loginifo', [IomPersonnelController::class, 'loginifo']);
Route::post('/iom/access', [IomPersonnelController::class, 'access']);