<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservationTransport;
use App\Models\TransportConversation;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    /**
     * Liste des réservations avec statistiques.
     */
    public function index(Request $request)
    {
        $query = ReservationTransport::latest('date_reservation');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $reservations = $query->paginate(15);

        $stats = [
            'total'       => ReservationTransport::count(),
            'en_attente'  => ReservationTransport::where('statut', 'en_attente')->count(),
            'en_cours'    => ReservationTransport::whereIn('statut', ['accepte', 'chauffeur_en_route', 'chauffeur_arrive', 'en_cours'])->count(),
            'termine'     => ReservationTransport::where('statut', 'termine')->count(),
            'revenus'     => ReservationTransport::where('statut', 'termine')->whereNotNull('prix_propose')->sum('prix_propose'),
        ];

        return view('admin.transport.index', compact('reservations', 'stats'));
    }

    /**
     * Détail d'une réservation (chat + carte + actions).
     */
    public function show(string $id)
    {
        $reservation = ReservationTransport::with('conversations')->findOrFail($id);
        return view('admin.transport.show', compact('reservation'));
    }

    /**
     * Envoyer un message dans le chat côté admin.
     */
    public function sendMessage(Request $request, string $id)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $reservation = ReservationTransport::findOrFail($id);

        $msg = TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'admin',
            'message' => $request->message,
            'type'    => 'message',
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', 'Message envoyé.');
    }

    /**
     * Admin propose un prix — crée un message spécial dans le chat.
     */
    public function proposePrice(Request $request, string $id)
    {
        $request->validate([
            'prix' => 'required|numeric|min:1',
        ]);

        $reservation = ReservationTransport::findOrFail($id);
        $reservation->update([
            'prix_propose' => $request->prix,
            'prix_accepte' => false, // Réinitialise si nouvelle proposition
        ]);

        $montantFormate = number_format($request->prix, 0, ',', ' ');

        TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'admin',
            'message' => "💰 Proposition de prix : {$montantFormate} FCFA pour votre course. Veuillez accepter ou nous contacter si vous souhaitez négocier.",
            'type'    => 'proposition_prix',
            'montant' => $request->prix,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "Prix de {$montantFormate} FCFA proposé au client.");
    }

    /**
     * Mettre à jour le statut d'une réservation.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,accepte,chauffeur_en_route,chauffeur_arrive,en_cours,termine,annule',
        ]);

        $reservation = ReservationTransport::findOrFail($id);
        $ancienStatut = $reservation->statut;
        $reservation->update(['statut' => $request->statut]);

        // Message automatique dans le chat selon le changement de statut
        $messages = [
            'accepte'            => "✅ Votre réservation a été acceptée ! Nous vous contacterons pour confirmer l'heure exacte.",
            'chauffeur_en_route' => "🚗 Votre chauffeur est en route ! Vous pouvez suivre sa position sur la carte.",
            'chauffeur_arrive'   => "📍 Votre chauffeur est arrivé à votre point de départ !",
            'en_cours'           => "🚀 Votre course a démarré. Bon voyage !",
            'termine'            => "🏁 Course terminée. Merci de nous avoir fait confiance !",
            'annule'             => "❌ Votre réservation a été annulée. Contactez-nous pour plus d'informations.",
        ];

        if (isset($messages[$request->statut]) && $ancienStatut !== $request->statut) {
            TransportConversation::create([
                'reservation_transport_id' => $reservation->id,
                'auteur'  => 'systeme',
                'message' => $messages[$request->statut],
                'type'    => 'notification_systeme',
            ]);
        }

        // Si chauffeur arrivé : mettre à jour le champ dédié
        if ($request->statut === 'chauffeur_arrive') {
            $reservation->update([
                'chauffeur_arrived'    => true,
                'chauffeur_arrived_at' => now(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'statut' => $request->statut]);
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    /**
     * Notifier l'arrivée du chauffeur (séparé du changement de statut pour flexibilité).
     */
    public function notifyArrival(string $id)
    {
        $reservation = ReservationTransport::findOrFail($id);
        $reservation->update([
            'chauffeur_arrived'    => true,
            'chauffeur_arrived_at' => now(),
            'statut'               => 'chauffeur_arrive',
        ]);

        TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'systeme',
            'message' => "📍 Votre chauffeur est arrivé à votre point de départ !",
            'type'    => 'notification_systeme',
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Générer le lien de tracking chauffeur (retourné en JSON).
     */
    public function generateDriverLink(string $id)
    {
        $reservation = ReservationTransport::findOrFail($id);

        // Met à jour le statut si ce n'est pas déjà "en route"
        if (!in_array($reservation->statut, ['chauffeur_en_route', 'chauffeur_arrive', 'en_cours'])) {
            $reservation->update(['statut' => 'chauffeur_en_route']);

            TransportConversation::create([
                'reservation_transport_id' => $reservation->id,
                'auteur'  => 'systeme',
                'message' => "🚗 Votre chauffeur est en route ! Vous pouvez suivre sa position sur la carte.",
                'type'    => 'notification_systeme',
            ]);
        }

        $driverUrl = route('driver.show', $reservation->driver_token);

        return response()->json([
            'success'    => true,
            'driver_url' => $driverUrl,
            'token'      => $reservation->driver_token,
        ]);
    }

    /**
     * Polling des messages pour l'admin.
     */
    public function getMessages(Request $request, string $id)
    {
        $reservation = ReservationTransport::findOrFail($id);
        $sinceId = $request->get('since_id', 0);

        $messages = TransportConversation::where('reservation_transport_id', $id)
            ->where('id', '>', $sinceId)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages'     => $messages,
            'prix_accepte' => $reservation->prix_accepte,
            'statut'       => $reservation->statut,
        ]);
    }

    // Resource methods stub (create/edit/update/destroy gérés autrement)
    public function create() { return redirect()->route('admin.transport.index'); }
    public function store(Request $request) { return redirect()->route('admin.transport.index'); }
    public function edit(string $id) { return redirect()->route('admin.transport.show', $id); }
    public function update(Request $request, string $id) { return redirect()->route('admin.transport.index'); }

    public function destroy(string $id)
    {
        ReservationTransport::findOrFail($id)->delete();
        return redirect()->route('admin.transport.index')->with('success', 'Réservation supprimée.');
    }
}
