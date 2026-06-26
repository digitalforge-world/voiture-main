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
use App\Http\Controllers\TransportController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CarViewerController;
// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/global-search', [App\Http\Controllers\GlobalSearchController::class, 'search'])->name('api.global-search');

// Routes Publiques - Services (Maintenant accessibles sans auth)
Route::get('/voitures', [PublicVoitureController::class, 'index'])->name('cars.index');
Route::get('/voitures/{voiture}', [PublicVoitureController::class, 'show'])->name('cars.show');
Route::post('/voitures/{voiture}/order', [PublicVoitureController::class, 'order'])->name('cars.order');

Route::get('/pieces', [PublicPieceController::class, 'index'])->name('parts.index');
Route::get('/pieces/compatibilite', [PublicPieceController::class, 'searchCompatibility'])->name('parts.compatibility');
Route::post('/pieces/{id}/acheter', [PublicPieceController::class, 'buy'])->name('parts.buy');
Route::post('/api/pieces/checkout', [PublicPieceController::class, 'apiCheckout'])->name('parts.api.checkout');

Route::get('/location', [RentalController::class, 'index'])->name('rental.index');
Route::post('/location/{id}/reserver', [RentalController::class, 'book'])->name('rental.book');

Route::get('/revisions', [RevisionController::class, 'create'])->name('revisions.create');
Route::post('/revisions', [RevisionController::class, 'store'])->name('revisions.store');
Route::post('/revisions/chat/start', [RevisionController::class, 'startChat'])->name('revisions.chat.start');
Route::post('/revisions/chat/send', [RevisionController::class, 'sendMessage'])->name('revisions.chat.send');
Route::post('/revisions/chat/close', [RevisionController::class, 'closeChat'])->name('revisions.chat.close');

// ─── Transport avec Chauffeur ─────────────────────────────────────────────────
Route::get('/transport', [TransportController::class, 'index'])->name('transport.index');
Route::post('/transport/reserver', [TransportController::class, 'store'])->name('transport.store');
Route::get('/transport/suivi/{tracking}', [TransportController::class, 'suivi'])->name('transport.suivi');
Route::post('/transport/message', [TransportController::class, 'sendMessage'])->name('transport.message');
Route::post('/transport/accepter-prix', [TransportController::class, 'acceptPrice'])->name('transport.accept-price');
Route::get('/transport/messages/{tracking}', [TransportController::class, 'getMessages'])->name('transport.get-messages');
Route::get('/transport/driver-location/{tracking}', [TransportController::class, 'getDriverLocation'])->name('transport.driver-location');
Route::post('/transport/update-trajet', [TransportController::class, 'updateTrajet'])->name('transport.update-trajet');

// ─── Interface & Authentification Chauffeur ──────────────────────────────────
Route::prefix('chauffeur')->name('driver.')->group(function () {
    Route::get('/login', [DriverController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DriverController::class, 'login'])->name('login.submit');
    Route::post('/logout', [DriverController::class, 'logout'])->name('logout');

    Route::middleware([\App\Http\Middleware\AuthenticateDriver::class])->group(function () {
        Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
        Route::get('/historique', [DriverController::class, 'history'])->name('history');
        Route::get('/{token}', [DriverController::class, 'show'])->name('show');
        Route::post('/{token}/location', [DriverController::class, 'updateLocation'])->name('update-location');
        Route::post('/{token}/arrive', [DriverController::class, 'markArrived'])->name('arrived');
    });
});

// Nouvelle fonctionnalité : Suivi de Commande (Tracking)
Route::get('/suivi', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/suivi/recherche', [TrackingController::class, 'track'])->name('tracking.search');
Route::get('/suivi/succes', function () {
    if (!session('tracking_number')) {
        return redirect()->route('home');
    }
    return view('tracking.success');
})->name('tracking.success');
Route::post('/suivi/pdf', [TrackingController::class, 'downloadPdf'])->name('tracking.pdf');

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

    // Media Deletion - Voitures
    Route::delete('cars/photos/{photo}', [App\Http\Controllers\Admin\VoitureController::class, 'deletePhoto'])->name('cars.photos.destroy');
    Route::delete('cars/videos/{video}', [App\Http\Controllers\Admin\VoitureController::class, 'deleteVideo'])->name('cars.videos.destroy');

    // Media Deletion - Pièces
    Route::delete('parts-inventory/photos/{photo}', [App\Http\Controllers\Admin\PieceController::class, 'deletePhoto'])->name('parts-inventory.photos.destroy');
    Route::delete('parts-inventory/videos/{video}', [App\Http\Controllers\Admin\PieceController::class, 'deleteVideo'])->name('parts-inventory.videos.destroy');

    // Gestion de la Flotte de Location (véhicules)
    Route::resource('fleet', App\Http\Controllers\Admin\FleetController::class);
    Route::delete('fleet/photos/{photo}', [App\Http\Controllers\Admin\FleetController::class, 'deletePhoto'])->name('fleet.photos.destroy');

    // Media Deletion - Révisions
    Route::delete('revisions/photos/{photo}', [App\Http\Controllers\Admin\RevisionController::class, 'deletePhoto'])->name('revisions.photos.destroy');

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

    // === Transport avec Chauffeur ===
    Route::resource('transport', App\Http\Controllers\Admin\TransportController::class);
    Route::post('transport/{id}/message', [App\Http\Controllers\Admin\TransportController::class, 'sendMessage'])->name('transport.message');
    Route::post('transport/{id}/proposer-prix', [App\Http\Controllers\Admin\TransportController::class, 'proposePrice'])->name('transport.price');
    Route::post('transport/{id}/statut', [App\Http\Controllers\Admin\TransportController::class, 'updateStatus'])->name('transport.status');
    Route::get('transport/{id}/messages', [App\Http\Controllers\Admin\TransportController::class, 'getMessages'])->name('transport.get-messages');
    Route::post('transport/{id}/chauffeur-arrive', [App\Http\Controllers\Admin\TransportController::class, 'notifyArrival'])->name('transport.arrival');
    Route::get('transport/{id}/driver-link', [App\Http\Controllers\Admin\TransportController::class, 'generateDriverLink'])->name('transport.driver-link');
    Route::post('transport/{id}/assign-driver', [App\Http\Controllers\Admin\TransportController::class, 'assignDriver'])->name('transport.assign-driver');
    Route::resource('drivers', App\Http\Controllers\Admin\DriverController::class);
});


// ─── Viewer 360° ─────────────────────────────────────────────────────────────
Route::prefix('viewer')->name('viewer.')->group(function () {
    // Public : consulter les viewers
    Route::get('/', [CarViewerController::class, 'index'])->name('index');

    // ✅ Protégé admin : créer/uploader (AVANT /{slug} pour éviter le conflit de route)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/create',    [CarViewerController::class, 'create'])->name('create');
        Route::post('/',         [CarViewerController::class, 'store'])->name('store');
        Route::delete('/{slug}', [CarViewerController::class, 'destroy'])->name('destroy');
    });

    // Public : afficher un viewer (après /create pour éviter le conflit)
    Route::get('/{slug}', [CarViewerController::class, 'show'])->name('show');
});

Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Redirection des routes auth standards vers l'accueil (sécurité par obscurité)
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');
Route::get('/register', function () {
    return redirect()->route('home');
})->name('register');