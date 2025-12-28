<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoitureController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\TrackingController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes Publiques - Services (Maintenant accessibles sans auth)
Route::get('/cars', [VoitureController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [VoitureController::class, 'show'])->name('cars.show');
Route::post('/cars/{id}/order', [VoitureController::class, 'order'])->name('cars.order');

Route::get('/parts', [PieceController::class, 'index'])->name('parts.index');
Route::get('/parts/compatibility', [PieceController::class, 'searchCompatibility'])->name('parts.compatibility');
Route::post('/parts/{id}/buy', [PieceController::class, 'buy'])->name('parts.buy');

Route::get('/rental', [RentalController::class, 'index'])->name('rental.index');
Route::post('/rental/{id}/book', [RentalController::class, 'book'])->name('rental.book');

Route::get('/revisions', [RevisionController::class, 'create'])->name('revisions.create');
Route::post('/revisions', [RevisionController::class, 'store'])->name('revisions.store');

// Nouvelle fonctionnalité : Suivi de Commande (Tracking)
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking/search', [TrackingController::class, 'track'])->name('tracking.search');
Route::get('/tracking/success', function () {
    if (!session('tracking_number')) {
        return redirect()->route('home');
    }
    return view('tracking.success');
})->name('tracking.success');

// Routes Admin (Sécurisées et cachées)
// URL secrète : /admin/portal/access/login/secure/{token}
Route::match(['get', 'post'], '/admin/portal/access/login/secure/X7z9Q2mL5v', function (Illuminate\Http\Request $request) {
    if ($request->isMethod('post')) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }

    return view('auth.login');
})->name('admin.login.url');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Redirection des routes auth standards vers l'accueil (sécurité par obscurité)
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');
Route::get('/register', function () {
    return redirect()->route('home');
})->name('register');