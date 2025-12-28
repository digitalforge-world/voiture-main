<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoitureController as PublicVoitureController;
use App\Http\Controllers\PieceController as PublicPieceController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\TrackingController;
// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes Publiques - Services (Maintenant accessibles sans auth)
Route::get('/cars', [PublicVoitureController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [PublicVoitureController::class, 'show'])->name('cars.show');
Route::post('/cars/{id}/order', [PublicVoitureController::class, 'order'])->name('cars.order');

Route::get('/parts', [PublicPieceController::class, 'index'])->name('parts.index');
Route::get('/parts/compatibility', [PublicPieceController::class, 'searchCompatibility'])->name('parts.compatibility');
Route::post('/parts/{id}/buy', [PublicPieceController::class, 'buy'])->name('parts.buy');

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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/search', [AdminController::class, 'globalSearch'])->name('global-search');

    // Gestion des Utilisateurs
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Gestion des Véhicules
    Route::resource('cars', App\Http\Controllers\Admin\VoitureController::class);

    // Gestion des Ports
    Route::resource('ports', App\Http\Controllers\Admin\PortController::class);

    // Gestion des Commandes Voitures
    Route::resource('orders-cars', App\Http\Controllers\Admin\CommandeVoitureController::class);

    // Gestion des Locations
    Route::resource('rentals', App\Http\Controllers\Admin\LocationController::class);

    // Gestion des Pièces
    Route::resource('parts-inventory', App\Http\Controllers\Admin\PieceController::class);

    // Media Deletion
    Route::delete('cars/photos/{photo}', [App\Http\Controllers\Admin\VoitureController::class, 'deletePhoto'])->name('cars.photos.destroy');
    Route::delete('cars/videos/{video}', [App\Http\Controllers\Admin\VoitureController::class, 'deleteVideo'])->name('cars.videos.destroy');
    Route::delete('parts-inventory/photos/{photo}', [App\Http\Controllers\Admin\PieceController::class, 'deletePhoto'])->name('parts-inventory.photos.destroy');
    Route::delete('parts-inventory/videos/{video}', [App\Http\Controllers\Admin\PieceController::class, 'deleteVideo'])->name('parts-inventory.videos.destroy');

    // Gestion des Commandes Pièces
    Route::resource('orders-parts', App\Http\Controllers\Admin\CommandePieceController::class);
    // Gestion des Révisions
    Route::resource('revisions', App\Http\Controllers\Admin\RevisionController::class);
    // Gestion des Paiements
    Route::resource('payments', App\Http\Controllers\Admin\PaiementController::class);
    // Paramètres
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('settings/update-bulk', [App\Http\Controllers\Admin\SettingController::class, 'updateBulk'])->name('settings.update-bulk');
    // Rapports, Contenu, Logs
    Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    Route::get('content', [App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content');
    Route::get('logs', [App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs');

    // === Nouveaux Modules ===
    // Marketing & Coupons
    Route::resource('coupons', App\Http\Controllers\Admin\MarketingCouponController::class);
    // Fournisseurs & Partenaires
    Route::resource('suppliers', App\Http\Controllers\Admin\PartnerSupplierController::class);
    // Support Client & Tickets
    Route::resource('tickets', App\Http\Controllers\Admin\SupportTicketController::class);
    Route::post('tickets/{ticket}/reply', [App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])->name('tickets.reply');
    // Facturation
    Route::resource('invoices', App\Http\Controllers\Admin\AccountingInvoiceController::class);
    Route::get('invoices/{invoice}/download', [App\Http\Controllers\Admin\AccountingInvoiceController::class, 'download'])->name('invoices.download');
});

Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Redirection des routes auth standards vers l'accueil (sécurité par obscurité)
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');
Route::get('/register', function () {
    return redirect()->route('home');
})->name('register');