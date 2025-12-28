<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommandeVoitureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\CommandeVoiture::with(['user', 'voiture']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_suivi', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('prenom', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('statut', $status);
        }

        $orders = $query->latest('date_commande')->paginate(15)->withQueryString();

        return view('admin.orders.cars.index', compact('orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = \App\Models\CommandeVoiture::findOrFail($id);

        $validated = $request->validate([
            'numero_suivi' => 'nullable|string|max:50',
            'statut' => 'required|in:en_attente,paye,importation,arrive,livre,annule',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders-cars.index')->with('success', 'Commande mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = \App\Models\CommandeVoiture::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders-cars.index')->with('success', 'Commande supprimée.');
    }
}
