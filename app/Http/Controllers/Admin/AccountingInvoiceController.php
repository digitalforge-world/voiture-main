<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountingInvoice;
use Illuminate\Http\Request;

class AccountingInvoiceController extends Controller
{
    public function index()
    {
        $invoices = AccountingInvoice::with('user')->latest()->paginate(20);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('admin.invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount_total' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'required|in:draft,sent,paid,cancelled'
        ]);

        // Generate invoice number
        $lastInvoice = AccountingInvoice::latest('id')->first();
        $number = 'INV-' . date('Y') . '-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);

        $invoice = AccountingInvoice::create([
            'invoice_number' => $number,
            'user_id' => $validated['user_id'],
            'amount_total' => $validated['amount_total'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date']
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès !');
    }

    public function show(AccountingInvoice $invoice)
    {
        $invoice->load('user');
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(AccountingInvoice $invoice)
    {
        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, AccountingInvoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,paid,cancelled',
            'due_date' => 'nullable|date',
            'paid_date' => 'nullable|date'
        ]);

        $invoice->update($validated);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Facture mise à jour !');
    }

    public function destroy(AccountingInvoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Facture supprimée !');
    }

    public function download(AccountingInvoice $invoice)
    {
        $invoice->load('user');
        $pdf = \PDF::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }
}
