<?php

namespace App\Http\Controllers;

use App\Models\ReservationTransport;
use App\Models\TransportConversation;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Interface mobile du chauffeur.
     */
    public function show(string $token)
    {
        $reservation = ReservationTransport::where('driver_token', $token)->firstOrFail();

        // Seules les courses actives permettent le tracking
        abort_if(
            in_array($reservation->statut, ['termine', 'annule']),
            403,
            'Cette course est terminée ou annulée.'
        );

        return view('transport.chauffeur', compact('reservation'));
    }

    /**
     * Le chauffeur envoie sa position GPS (appelé toutes les 5s depuis le navigateur).
     */
    public function updateLocation(Request $request, string $token)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $reservation = ReservationTransport::where('driver_token', $token)->firstOrFail();

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
