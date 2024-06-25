<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\BackupController;


Route::post('/run-backup', [BackupController::class, 'runBackup'])->name('run-backup');

// Welcome and Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

Auth::routes([
    'verify' => true
]);

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard'); // Points to the dashboard view
})->middleware(['auth', 'verified'])->name('dashboard');

// Task Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/completed', [TaskController::class, 'completed'])->name('tasks.completed');
    Route::post('/tasks/complete/{id}', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/edit/{id}', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/update/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::delete('/tasks/completed/{id}', [TaskController::class, 'destroyCompleted'])->name('tasks.destroyCompleted');

    
    
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Page
Route::get('/adminpage', [HomeController::class, 'index'])->middleware('admin');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// Additional Routes
Route::get('send-mail', [EmailController::class, 'sendWelcomeEmail']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send-message');


// Include default authentication routes
require __DIR__.'/auth.php';
