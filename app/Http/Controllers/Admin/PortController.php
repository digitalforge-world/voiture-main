<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Port;
use Illuminate\Database\QueryException;
class PortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ports = Port::latest()->get();
        return view('admin.ports.index', compact('ports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'code' => 'nullable|string|max:10|unique:ports,code',
            'frais_portuaires' => 'required|numeric|min:0',
            'delai_estime' => 'required|integer|min:0',
            'type' => 'required|in:maritime,terrestre,mixte',
        ]);

        $port = new Port();
        $port->nom = $validated['nom'];
        $port->pays = $validated['pays'];
        $port->ville = $validated['ville'];
        $port->code = $validated['code'] ?? strtoupper(substr($validated['nom'], 0, 3)) . rand(100, 999);
        $port->frais_base = $validated['frais_portuaires'];
        $port->delai_moyen_jours = $validated['delai_estime'];
        $port->type = $validated['type'];
        $port->save();

        return redirect()->route('admin.ports.index')->with('success', 'Nouveau point d\'entrée portuaire configuré.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $port = Port::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'frais_portuaires' => 'required|numeric|min:0',
            'delai_estime' => 'required|integer|min:0',
            'type' => 'required|in:maritime,terrestre,mixte',
        ]);

        $port->nom = $validated['nom'];
        $port->pays = $validated['pays'];
        $port->ville = $validated['ville'];
        $port->frais_base = $validated['frais_portuaires'];
        $port->delai_moyen_jours = $validated['delai_estime'];
        $port->type = $validated['type'];
        $port->save();

        return redirect()->route('admin.ports.index')->with('success', 'Configuration logistique mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $port = Port::findOrFail($id);
            $port->delete();
            return redirect()->route('admin.ports.index')->with('success', 'Point d\'entrée retiré du réseau.');
        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('admin.ports.index')->with('error', 'Impossible de supprimer ce port : il est utilisé dans des commandes ou des véhicules.');
            }
            return redirect()->route('admin.ports.index')->with('error', 'Erreur lors de la suppression du port.');
        }
    }
}
