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
use App\Http\Controllers\EmpDataAndStatusController;
use App\Http\Controllers\EmpLeaveBalanceController;
use App\Http\Controllers\EmpObjectivesController;
use App\Http\Controllers\EmpEvaluatedController;
use App\Http\Controllers\AttPayrollController;
use App\Http\Controllers\SysAdminLogsController;
use App\Http\Controllers\EmpGeneralInfoController;
use App\Http\Controllers\EmpContractHistoryController;
use App\Http\Controllers\EmpDocumentsController;
use App\Http\Controllers\EmpSalaryInformationController;
use App\Http\Controllers\EmpTokensController;
use App\Http\Controllers\DataEvaluationScheduleController;
use App\Http\Controllers\EmpEvaluationsController;
use App\Http\Controllers\EmpLeaveRequestsController;
use App\Http\Controllers\TestController;

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
Route::post('/admin/logininfo', [SocAdminsController::class, 'logininfo']);
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

// Employees API - Lister
Route::post('/employee/list/all', [EmpGeneralInfoController::class, 'showall']);
Route::post('/employee/list/info', [EmpGeneralInfoController::class, 'showinfo']);

// Employees API - History
Route::post('/employee/history/show', [EmpContractHistoryController::class, 'showhistory']);
Route::post('/employee/history/current', [EmpContractHistoryController::class, 'getcurrent']);
Route::post('/employee/history/new', [EmpContractHistoryController::class, 'addhistory']);
Route::post('/employee/history/edit', [EmpContractHistoryController::class, 'edithistory']);
Route::post('/employee/history/clearcurrent', [EmpContractHistoryController::class, 'clearcurrent']);
Route::post('/employee/history/delete', [EmpContractHistoryController::class, 'deletehistory']);
Route::post('/employee/history/getcontend', [EmpContractHistoryController::class, 'contractend']);

// Employees API - Documents
Route::post('/employee/documents/show', [EmpDocumentsController::class, 'listfiles']);
Route::post('/employee/documents/add', [EmpDocumentsController::class, 'addfile']);
Route::post('/employee/documents/delete', [EmpDocumentsController::class, 'deletefile']);
Route::post('/employee/documents/download', [EmpDocumentsController::class, 'zipandownload']);
Route::post('/employee/documents/directory', [EmpDocumentsController::class, 'createdirectory']);

// Employee API - General Information
Route::post('/employee/generalinfo/edit', [EmpGeneralInfoController::class, 'editgeneralinfo']);
Route::post('/employee/generalinfo/contact', [EmpGeneralInfoController::class, 'editcontactinfo']);
Route::post('/employee/generalinfo/status', [EmpGeneralInfoController::class, 'editstatus']);
Route::post('/employee/generalinfo/new', [EmpGeneralInfoController::class, 'addgeneralinfo']);
Route::post('/employee/jobinfo/edit', [EmpGeneralInfoController::class, 'editjobinfo']);
Route::post('/employee/jobinfo/supervisors', [EmpGeneralInfoController::class, 'editsupervisors']);
Route::post('/employee/jobinfo/new', [EmpGeneralInfoController::class, 'addjobinfo']);
Route::post('/employee/generalinfo/ref', [EmpGeneralInfoController::class, 'getSoc']);

// Employees API - Salary
Route::post('/employee/salary/show', [EmpSalaryInformationController::class, 'getsalary']);
Route::post('/employee/salary/edit', [EmpSalaryInformationController::class, 'editsalary']);
Route::post('/employee/salary/new', [EmpSalaryInformationController::class, 'addsalary']);

// Employees API - Objective
Route::post('employee/objective/new', [EmpObjectivesController::class, 'creatobjective']);

// Employees API - Add New Employee
Route::post('employee/new', [EmpDataAndStatusController::class, 'newemployee']);
Route::post('employee/import', [EmpDataAndStatusController::class, 'importdata']);

// Employee API - Objective
Route::post('/objective/byemp', [EmpObjectivesController::class, 'getobjectivebyemp']);
Route::post('/objective/bytoken', [EmpObjectivesController::class, 'getobjectivebytoken']);
Route::post('/objective/edit', [EmpObjectivesController::class, 'editobjective']);
Route::post('/objective/approve/supervisor', [EmpObjectivesController::class, 'approvebysupervisor']);
Route::post('/objective/approve/headofsuboffice', [EmpObjectivesController::class, 'approvebyheadofsuboffice']);

// Employee API - Leaves
Route::post('/leave/list', [EmpLeaveRequestsController::class, 'listleave']);
Route::post('/leave/show', [EmpLeaveRequestsController::class, 'showleave']);
Route::post('/leave/request', [EmpLeaveRequestsController::class, 'requestleave']);
Route::post('/leave/socapprove', [EmpLeaveRequestsController::class, 'approvesoc']);
Route::post('/leave/supervisorapprove', [EmpLeaveRequestsController::class, 'approvesupervisor']);
Route::post('/leave/headofsubofficeapprove', [EmpLeaveRequestsController::class, 'approveheadofsuboffice']);
Route::post('/leave/cancel', [EmpLeaveRequestsController::class, 'requestcancellation']);
Route::post('/leave/supervisorcancel', [EmpLeaveRequestsController::class, 'cancelsupervisor']);
Route::post('/leave/headofsubofficecancel', [EmpLeaveRequestsController::class, 'cancelheadofsuboffice']);

// Employee API - Evaluations
Route::post('/evaluation/list', [EmpEvaluationsController::class, 'listevaluations']);
Route::post('/evaluation/show', [EmpEvaluationsController::class, 'showevaluation']);
Route::post('/evaluation/requestsupervisor', [EmpEvaluationsController::class, 'requestsupervisor']);
Route::post('/evaluation/requestheadofsuboffice', [EmpEvaluationsController::class, 'requestheadofsuboffice']);
Route::post('/evaluation/supervisor', [EmpEvaluationsController::class, 'supervisorevaluation']);
Route::post('/evaluation/headofsuboffice', [EmpEvaluationsController::class, 'headofsubofficeevaluation']);
Route::post('/evaluation/comment', [EmpEvaluationsController::class, 'commentevaluatin']);
Route::post('/evaluation/skip', [EmpEvaluationsController::class, 'skipheadofsuboffice']);
Route::post('/evaluation/delete', [EmpEvaluationsController::class, 'deleteevaluation']);

//Payroll API
Route::post('/payroll/list', [AttPayrollController::class, 'list']);
Route::post('/payroll/totals', [AttPayrollController::class, 'sums']);
Route::post('/payroll/delete', [AttPayrollController::class, 'delete']);
Route::post('/payroll/show', [AttPayrollController::class, 'show']);

// Employees Data and Status API
Route::post('/emps/activate', [EmpDataAndStatusController::class, 'statuschanger']);

// Emplyees Evaluations And Objectives Stats API
Route::post('/evaluations/stats/evaluated', [EmpEvaluatedController::class, 'evaluated']);
Route::post('/evaluations/stats/notevaluated', [EmpEvaluatedController::class, 'notevaluated']);
Route::post('/evaluations/stats/list', [EmpEvaluatedController::class, 'evaluations']);
Route::post('/evaluations/stats/nobjectives', [EmpEvaluatedController::class, 'nobjectives']);
Route::post('/evaluations/stats/pendingbjectives', [EmpEvaluatedController::class, 'pendingbjectives']);
Route::post('/evaluations/stats/approvedbjectives', [EmpEvaluatedController::class, 'approvedbjectives']);
Route::post('/evaluations/scehdule/create', [DataEvaluationScheduleController::class, 'newsched']);

// Emplyees Leave Balance API
Route::post('/leave/balance/increase', [EmpLeaveBalanceController::class, 'increasebalance']);
Route::post('/leave/balance/update', [EmpLeaveBalanceController::class, 'increasebalanceemp']);
Route::post('/leave/balance/show', [EmpLeaveBalanceController::class, 'show']);
Route::post('/leave/balance/import', [EmpLeaveBalanceController::class, 'importbalance']);
Route::post('/leave/balance/rotate', [EmpLeaveBalanceController::class, 'rotatebalance']);

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

// Logs API
Route::post('/logs/admin/list', [SysAdminLogsController::class, 'alllogs']);
Route::post('/logs/admin/byadmin', [SysAdminLogsController::class, 'logsbyadmin']);
Route::post('/logs/admin/clear', [SysAdminLogsController::class, 'clearlogs']);

// FireBase Token
Route::post('/employee/token/add', [EmpTokensController::class, 'store']);
Route::post('/employee/token/remove', [EmpTokensController::class, 'destroy']);

// Test API
Route::get('/test/listds', [TestController::class, 'listds']);
Route::post('/test/normalize', [TestController::class, 'getOrCreateDutyStationId']);
