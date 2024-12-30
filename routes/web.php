<?php

use App\Http\Controllers\EmployeeCOEController;
use App\Http\Controllers\EmployeeQuestionnareController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeRequestClearanceController;
use App\Http\Controllers\OfficialRequestController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ResponsesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SendMailController;

// Authentication Routes
Route::get('/', [LoginController::class, 'index'])->name('login');

Route::middleware('guest')->group(function () {
    Route::get('register', [LoginController::class, 'register'])->name('register');
    Route::get('forgot_password', [AuthController::class, 'forgot_password'])->name('forgot_password');
});

Route::post('process_register', [AuthController::class, 'proccess_register'])->name('process_register');
Route::post('process_login', [AuthController::class, 'proccess_login']);
Route::post('process_forgot_password', [AuthController::class, 'process_forgot_password'])->name('process_forgot_password');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::get('home', [AuthController::class, 'home'])->name('home')->middleware('can:access-home');
    Route::get('hr', [AuthController::class, 'hr_dashboard'])->name('hr_dashboard')->middleware('can:access-hr');
    Route::get('official', [AuthController::class, 'official_dashboard'])->name('official_dashboard')->middleware('can:access-official');

    // User Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('add-user', [UserController::class, 'create'])->name('users.add');
    Route::post('add-user', [UserController::class, 'store'])->name('users.store');
    Route::get('user-details/{id}', [UserController::class, 'details'])->name('users.details');
    Route::post('update-user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('disable-user/{id}', [UserController::class, 'disable'])->name('users.disable');
    Route::post('new-activate-user/{id}', [UserController::class, 'new_activate'])->name('users.new_activate');
    Route::get('change_password', [UserController::class, 'change_password'])->name('change_password');
    Route::post('process_change_password', [UserController::class, 'process_change_password'])->name('users.process_change_password');

    // Clearance Management
    Route::get('clearances', [ClearanceController::class, 'index'])->name('clearance.index');
    Route::post('clearance-store', [ClearanceController::class, 'store'])->name('clearance.store');
    Route::get('clearance-details/{id}', [ClearanceController::class, 'details'])->name('clearance.details');
    Route::post('clearance-update/{id}', [ClearanceController::class, 'update'])->name('clearance.update');
    Route::post('add-comment/{id}', [ClearanceController::class, 'comment'])->name('clearance.comment');

    // Requests Management
    Route::get('requests', [RequestController::class, 'index'])->name('clearance_request.index');
    Route::post('request-status/{id}', [RequestController::class, 'update_status'])->name('request.status');

    // Profile Routes
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('update-profile/{id}', [ProfileController::class, 'update'])->name('profile.update');

    // Employee Clearance
    Route::get('clearance', [EmployeeRequestClearanceController::class, 'index'])->name('employee_clearance.index');
    Route::post('sumbit-request', [EmployeeRequestClearanceController::class, 'store'])->name('employee_clearance.store');

    // Employee COE
    Route::get('COE', [EmployeeCOEController::class, 'index'])->name('employee_coe.index');
    Route::get('COE/download/{id}', [EmployeeCOEController::class, 'download'])->name('employee_coe.download');

    // Send Email
    Route::post('send_email', [SendMailController::class, 'Send_email'])->name('send_email');

    // Official Requests
    Route::get('clearance_requests', [OfficialRequestController::class, 'index'])->name('official_requests.index');

    Route::post('official-request-status/{id}', [RequestController::class, 'official_update_status'])->name('official_request.status');


    // HR Questionnaire
    Route::get('questionnaire', [QuestionnaireController::class, 'index'])->name('hr_questionnaire.index');
    Route::post('question-store', [QuestionnaireController::class, 'store'])->name('hr_questionnaire.store');
    Route::post('questions/{id}', [QuestionnaireController::class, 'update'])->name('hr_questionnaire.update');
    Route::post('questions/{id}', [QuestionnaireController::class, 'delete'])->name('hr_questionnaire.delete');
    // generate COE
    Route::get('certificate-of-employment',[RequestController::class, 'certificate_of_employment'])->name('request.coe');
    Route::post('generate-certificate-of-employment/{id}',[RequestController::class, 'generate_certificate_of_employment'])->name('request.generate.coe');

    Route::get('qna', [EmployeeQuestionnareController::class, 'index'])->name('employee_clearance.questionnaire.index');
    Route::post('qna-store/{id}', [EmployeeQuestionnareController::class, 'store'])->name('employee_clearance.questionnaire.store');

    Route::get('responses', [ResponsesController::class, 'index'])->name('hr_questionnaire.responses.index');
});

// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
