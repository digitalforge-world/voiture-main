<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerSupplier;
use Illuminate\Http\Request;

class PartnerSupplierController extends Controller
{
    public function index()
    {
        $suppliers = PartnerSupplier::latest()->paginate(20);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:dealer,auction,logistics,service,other',
            'contact_person' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        PartnerSupplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Fournisseur créé avec succès !');
    }

    public function show(PartnerSupplier $supplier)
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(PartnerSupplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, PartnerSupplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:dealer,auction,logistics,service,other',
            'contact_person' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Fournisseur mis à jour avec succès !');
    }

    public function destroy(PartnerSupplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Fournisseur supprimé avec succès !');
    }
}
