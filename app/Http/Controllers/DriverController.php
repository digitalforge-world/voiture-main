<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\ReservationTransport;
use App\Models\TransportConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Afficher le formulaire de connexion pour le chauffeur.
     */
    public function showLoginForm()
    {
        if (session()->has('driver_authenticated_id')) {
            return redirect()->route('driver.dashboard');
        }
        return view('transport.driver_login');
    }

    /**
     * Authentifier le chauffeur.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifiant'  => 'required|string',
            'mot_de_passe' => 'required|string',
        ]);

        $driver = Driver::where('identifiant', $credentials['identifiant'])
            ->where('statut', 'actif')
            ->first();

        if ($driver && Hash::check($credentials['mot_de_passe'], $driver->mot_de_passe)) {
            session(['driver_authenticated_id' => $driver->id]);
            return redirect()->route('driver.dashboard')->with('success', 'Connexion réussie.');
        }

        return back()->withErrors([
            'identifiant' => 'Identifiant ou mot de passe incorrect, ou compte inactif.',
        ])->onlyInput('identifiant');
    }

    /**
     * Déconnexion du chauffeur.
     */
    public function logout()
    {
        session()->forget('driver_authenticated_id');
        return redirect()->route('driver.login')->with('success', 'Vous avez été déconnecté.');
    }

    /**
     * Tableau de bord du chauffeur.
     */
    public function dashboard()
    {
        $driverId = session('driver_authenticated_id');
        $driver = Driver::findOrFail($driverId);

        $activeReservations = ReservationTransport::where('driver_id', $driverId)
            ->whereNotIn('statut', ['termine', 'annule'])
            ->latest('date_prise_en_charge')
            ->get();

        $completedReservations = ReservationTransport::where('driver_id', $driverId)
            ->whereIn('statut', ['termine', 'annule'])
            ->latest('date_prise_en_charge')
            ->get();

        return view('transport.driver_dashboard', compact('driver', 'activeReservations', 'completedReservations'));
    }

    /**
     * Historique complet des courses du chauffeur.
     */
    public function history()
    {
        $driverId = session('driver_authenticated_id');
        $driver = Driver::findOrFail($driverId);

        $completedReservations = ReservationTransport::where('driver_id', $driverId)
            ->whereIn('statut', ['termine', 'annule'])
            ->latest('date_prise_en_charge')
            ->paginate(15);

        return view('transport.driver_history', compact('driver', 'completedReservations'));
    }

    /**
     * Interface mobile du chauffeur pour une course spécifique.
     */
    public function show(string $token)
    {
        $reservation = ReservationTransport::where('driver_token', $token)->firstOrFail();
        $driverId = session('driver_authenticated_id');

        // Sécurité : Vérifier que le chauffeur connecté est bien celui assigné à la course
        abort_if(
            $reservation->driver_id != $driverId,
            403,
            'Vous n\'êtes pas autorisé à accéder à cette course.'
        );

        // Seules les courses actives permettent le tracking
        abort_if(
            in_array($reservation->statut, ['termine', 'annule']),
            403,
            'Cette course est terminée ou annulée.'
        );

        return view('transport.chauffeur', compact('reservation'));
    }

    /**
     * Le chauffeur envoie sa position GPS.
     */
    public function updateLocation(Request $request, string $token)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $reservation = ReservationTransport::where('driver_token', $token)->firstOrFail();
        $driverId = session('driver_authenticated_id');

        abort_if(
            $reservation->driver_id != $driverId,
            403,
            'Course non autorisée.'
        );

        abort_if(
            in_array($reservation->statut, ['termine', 'annule']),
            403,
            'Course terminée.'
        );

        $reservation->update([
            'chauffeur_lat' => $request->lat,
            'chauffeur_lng' => $request->lng,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Chauffeur clique "Je suis arrivé" → déclenche la notification client.
     */
    public function markArrived(string $token)
    {
        $reservation = ReservationTransport::where('driver_token', $token)->firstOrFail();
        $driverId = session('driver_authenticated_id');

        abort_if(
            $reservation->driver_id != $driverId,
            403,
            'Course non autorisée.'
        );

        $reservation->update([
            'chauffeur_arrived'    => true,
            'chauffeur_arrived_at' => now(),
            'statut'               => 'chauffeur_arrive',
        ]);

        // Message automatique dans le chat
        TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'systeme',
            'message' => "📍 Votre chauffeur est arrivé à votre point de départ ! Rejoignez-le dès que possible.",
            'type'    => 'notification_systeme',
        ]);

        return response()->json(['success' => true]);
    }
}
