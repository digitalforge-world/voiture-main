<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'latestMessage'])->latest()->paginate(20);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('admin.tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string'
        ]);

        $ticket = SupportTicket::create([
            'user_id' => $validated['user_id'],
            'subject' => $validated['subject'],
            'priority' => $validated['priority'],
            'status' => 'open'
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_internal_note' => false
        ]);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket créé avec succès !');
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'messages.user']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function edit(SupportTicket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,answered,customer_reply,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket mis à jour !');
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket supprimé !');
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal_note' => 'boolean'
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_internal_note' => $request->has('is_internal_note')
        ]);

        $ticket->update(['status' => 'answered']);

        return back()->with('success', 'Réponse envoyée !');
    }
}
