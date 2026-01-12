<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuditorAssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirekturReviewController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\IndikatorKinerjaController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PelaksanaanController;
use App\Http\Controllers\PenetapanController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SPIController;
use App\Http\Controllers\StandarMutuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitAuditorController;
use App\Http\Controllers\BukuKebijakanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm']);
    Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
});

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::middleware('permission:standar-mutu')->group(function () {
        Route::get('/standar-mutu', [StandarMutuController::class, 'index']);
        Route::get('/standar-mutu/create', [StandarMutuController::class, 'create']);
        Route::post('/standar-mutu', [StandarMutuController::class, 'store']);
        Route::get('/standar-mutu/{id}/edit', [StandarMutuController::class, 'edit']);
        Route::put('/standar-mutu/{id}', [StandarMutuController::class, 'update']);
        Route::delete('/standar-mutu/{id}', [StandarMutuController::class, 'destroy']);
    });
    Route::middleware('permission:kriteria')->group(function () {
        Route::get('/kriteria', [KriteriaController::class, 'index']);
        Route::get('/kriteria/create', [KriteriaController::class, 'create']);
        Route::post('/kriteria', [KriteriaController::class, 'store']);
        Route::get('/kriteria/{id}', [KriteriaController::class, 'show']);
        Route::get('/kriteria/{id}/edit', [KriteriaController::class, 'edit']);
        Route::put('/kriteria/{id}', [KriteriaController::class, 'update']);
        Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy']);
        Route::post('/kriteria/{id}/resubmit', [KriteriaController::class, 'resubmit']);
    });
    Route::middleware('permission:indikator-kinerja')->group(function () {
        Route::get('/indikator-kinerja', [IndikatorKinerjaController::class, 'index']);
        Route::get('/indikator-kinerja/create', [IndikatorKinerjaController::class, 'create']);
        Route::post('/indikator-kinerja', [IndikatorKinerjaController::class, 'store']);
        Route::get('/indikator-kinerja/{id}', [IndikatorKinerjaController::class, 'show']);
        Route::get('/indikator-kinerja/{id}/edit', [IndikatorKinerjaController::class, 'edit']);
        Route::put('/indikator-kinerja/{id}', [IndikatorKinerjaController::class, 'update']);
        Route::delete('/indikator-kinerja/{id}', [IndikatorKinerjaController::class, 'destroy']);
        Route::post('/indikator-kinerja/{id}/resubmit', [IndikatorKinerjaController::class, 'resubmit']);
    });
    Route::middleware('permission:approval')->group(function () {
        Route::get('/approval', [ApprovalController::class, 'index']);
        Route::post('/approval', [ApprovalController::class, 'approve']);
        Route::post('/approval/reject', [ApprovalController::class, 'reject']);
        Route::get('/approval/approved', [ApprovalController::class, 'approved']);
        Route::get('/approval/penetapan', [ApprovalController::class, 'penetapan']);
    });
    Route::middleware('permission:penetapan')->group(function () {
        Route::get('/penetapan', [PenetapanController::class, 'index']);
        Route::get('/penetapan/create', [PenetapanController::class, 'create']);
        Route::post('/penetapan', [PenetapanController::class, 'store']);
        Route::post('/penetapan/{id}/resubmit', [PenetapanController::class, 'resubmit']);
        Route::get('/penetapan/{id}/edit', [PenetapanController::class, 'edit']);
        Route::put('/penetapan/{id}', [PenetapanController::class, 'update']);
        Route::delete('/penetapan/{id}', [PenetapanController::class, 'destroy']);
    });
    Route::middleware('permission:pelaksanaan')->group(function () {
        Route::get('/pelaksanaan', [PelaksanaanController::class, 'index']);
        Route::get('/pelaksanaan/create', [PelaksanaanController::class, 'create']);
        Route::post('/pelaksanaan', [PelaksanaanController::class, 'store']);
        Route::get('/pelaksanaan/{id}/edit', [PelaksanaanController::class, 'edit']);
        Route::put('/pelaksanaan/{id}', [PelaksanaanController::class, 'update']);
        Route::post('/pelaksanaan/{id}/assign-auditor', [PelaksanaanController::class, 'assignAuditor']);
        Route::delete('/pelaksanaan/{pelaksanaanId}/auditor/{auditorId}', [PelaksanaanController::class, 'removeAuditor']);
        Route::delete('/pelaksanaan/{id}', [PelaksanaanController::class, 'destroy']);
    });
    Route::middleware('permission:evaluasi')->group(function () {
        Route::get('/evaluasi', [EvaluasiController::class, 'index']);
        Route::get('/evaluasi/create', [EvaluasiController::class, 'create']);
        Route::post('/evaluasi', [EvaluasiController::class, 'store']);
        Route::get('/evaluasi/{id}', [EvaluasiController::class, 'show']);
        Route::get('/evaluasi/{id}/edit', [EvaluasiController::class, 'edit']);
        Route::put('/evaluasi/{id}', [EvaluasiController::class, 'update']);
        Route::delete('/evaluasi/{id}', [EvaluasiController::class, 'destroy']);
    });
    Route::middleware('permission:laporan')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index']);
        Route::get('/laporan/pdf', [LaporanController::class, 'pdf']);
    });
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::middleware('permission:users')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/create', [UserController::class, 'create']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}/edit', [UserController::class, 'edit']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
    Route::middleware('permission:roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/create', [RoleController::class, 'create']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    });
    Route::middleware('permission:units')->group(function () {
        Route::get('/units', [UnitController::class, 'index']);
        Route::get('/units/create', [UnitController::class, 'create']);
        Route::post('/units', [UnitController::class, 'store']);
        Route::get('/units/{id}/edit', [UnitController::class, 'edit']);
        Route::put('/units/{id}', [UnitController::class, 'update']);
        Route::delete('/units/{id}', [UnitController::class, 'destroy']);
    });
    Route::get('/auditor-assignments', [AuditorAssignmentController::class, 'index']);
    Route::post('/auditor-assignments', [AuditorAssignmentController::class, 'store']);
    Route::delete('/auditor-assignments/{id}', [AuditorAssignmentController::class, 'destroy']);
    Route::middleware('permission:unit-auditors')->group(function () {
        Route::get('/unit-auditors', [UnitAuditorController::class, 'index']);
        Route::post('/unit-auditors', [UnitAuditorController::class, 'store']);
        Route::delete('/unit-auditors/{id}', [UnitAuditorController::class, 'destroy']);
    });
    Route::middleware('permission:buku-kebijakan')->group(function () {
        Route::get('/kebijakan', [BukuKebijakanController::class, 'kebijakan']);
        Route::get('/manual', [BukuKebijakanController::class, 'manual']);
        Route::get('/formulir', [BukuKebijakanController::class, 'formulir']);
        Route::get('/buku-kebijakan/create/{tipe}', [BukuKebijakanController::class, 'create']);
        Route::post('/buku-kebijakan', [BukuKebijakanController::class, 'store']);
        Route::get('/buku-kebijakan/{id}/edit', [BukuKebijakanController::class, 'edit']);
        Route::put('/buku-kebijakan/{id}', [BukuKebijakanController::class, 'update']);
        Route::delete('/buku-kebijakan/{id}', [BukuKebijakanController::class, 'destroy']);
    });
    Route::middleware('permission:direktur-review')->group(function () {
        Route::get('/direktur-review', [DirekturReviewController::class, 'index']);
        Route::get('/direktur-review/{id}', [DirekturReviewController::class, 'show']);
        Route::put('/direktur-review/{id}', [DirekturReviewController::class, 'update']);
    });
    Route::middleware('permission:spi-monitoring')->group(function () {
        Route::get('/spi', [SPIController::class, 'index']);
        Route::post('/spi/send-notification', [SPIController::class, 'sendNotification']);
        Route::get('/spi/users', [SPIController::class, 'getUsers']);
    });
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
