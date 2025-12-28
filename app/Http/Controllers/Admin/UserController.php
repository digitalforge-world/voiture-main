<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::query();

        // Search Cluster
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('telephone', 'LIKE', "%{$search}%");
            });
        }

        // Role Filter
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Status Filter
        if ($status = $request->input('status')) {
            $query->where('actif', $status === 'active');
        }

        $users = $query->latest('date_creation')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
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
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:15',
            'role' => 'required|in:admin,agent,client',
            'mot_de_passe' => 'required|string|min:8',
        ]);

        $user = new \App\Models\User();
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->telephone = $validated['telephone'];
        $user->role = $validated['role'];
        $user->mot_de_passe = \Illuminate\Support\Facades\Hash::make($validated['mot_de_passe']);
        $user->actif = true;
        $user->date_creation = now();
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Compte créé avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:15',
            'role' => 'required|in:admin,agent,client',
            'actif' => 'required|boolean',
        ]);

        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->telephone = $validated['telephone'];
        $user->role = $validated['role'];
        $user->actif = $validated['actif'];
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Compte mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
