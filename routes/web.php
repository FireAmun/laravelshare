<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [FileController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::post('/upload', [FileController::class, 'store'])->name('file.upload');
Route::get('/d/{uuid}', [FileController::class, 'show'])->name('file.download');
Route::post('/d/{uuid}', [FileController::class, 'download'])->name('file.download.post');

Route::get('/dashboard', [FileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// Admin Login Routes
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
        $user = Auth::user();

        if (!$user->is_admin) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Access denied. Administrator privileges required.',
            ]);
        }

        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('admin.login.post');

// Admin Routes (Admin only)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [SecurityController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [SecurityController::class, 'users'])->name('users');
    Route::get('/files', [SecurityController::class, 'files'])->name('files');
    Route::get('/settings', [SecurityController::class, 'systemSettings'])->name('settings');
    Route::get('/chart-test', function () {
        return view('admin.chart-test');
    })->name('chart-test');

    // Admin Actions
    Route::delete('/files/{uuid}', [SecurityController::class, 'deleteFile'])->name('files.delete');
    Route::post('/users/{id}/toggle-status', [SecurityController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::post('/users/{id}/make-admin', [SecurityController::class, 'makeAdmin'])->name('users.make-admin');
    Route::post('/users/{id}/remove-admin', [SecurityController::class, 'removeAdmin'])->name('users.remove-admin');
    Route::post('/cleanup-expired-files', [SecurityController::class, 'cleanupExpiredFiles'])->name('cleanup-expired-files');
});

// Legacy route for backward compatibility
Route::get('/admin/security', [SecurityController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('security.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/files/{uuid}', [FileController::class, 'destroy'])->name('file.delete');
});

require __DIR__.'/auth.php';
