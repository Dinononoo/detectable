<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReportController;

// หน้าแสดงแบบฟอร์มอัปโหลด
Route::get('/', [ScheduleController::class, 'index'])->name('upload.index');

// อัปโหลดและประมวลผลไฟล์
Route::post('/upload', [ScheduleController::class, 'processSchedules'])->name('upload.store');

Route::get('/process-schedules', [ScheduleController::class, 'index'])->name('schedules.index');
Route::post('/process-schedules', [ScheduleController::class, 'processSchedules'])->name('processSchedules');

Route::get('/report', [ReportController::class, 'show'])->name('report.show');

Route::get('/report/downloadPDF', [ReportController::class, 'downloadPDF'])->name('report.downloadPDF');