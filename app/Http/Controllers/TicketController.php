<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with('projet')->get();
        return view('dashboard', compact('tickets'));
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with('projet')->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $projets = Projet::where('user_id', Auth::id())->get();
        return view('tickets.create', compact('projets'));
    }

    public function createForProjet($projetId)
    {
        $projet  = Projet::where('id', $projetId)->where('user_id', Auth::id())->firstOrFail();
        $projets = Projet::where('user_id', Auth::id())->get();
        return view('tickets.create', compact('projets', 'projet'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'   => 'required',
            'client'    => 'required',
            'due'       => 'required|date',
            'projet_id' => 'required|exists:projets,id',
        ]);

        Ticket::create([
            'sujet'       => $request->subject,
            'description' => $request->description,
            'client'      => $request->client,
            'priorite'    => $request->priority,
            'statut'      => $request->status,
            'due'         => $request->due,
            'user_id'     => Auth::id(),
            'projet_id'   => $request->projet_id,
        ]);

        if ($request->filled('redirect_projet_id')) {
            return redirect()->route('projets.show', $request->redirect_projet_id)
                             ->with('success', 'Ticket créé avec succès !');
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès !');
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'subject'     => 'required|string|max:255',
            'client'      => 'required|string|max:255',
            'description' => 'nullable|string',
            'due'         => 'required|date',
            'priority'    => 'nullable|in:basse,moyenne,haute',
            'status'      => 'nullable|in:ouvert,en cours,fermé',
            'projet_id'   => 'required|exists:projets,id',
        ]);

        $ticket = Ticket::create([
            'sujet'       => $validated['subject'],
            'description' => $validated['description'] ?? null,
            'client'      => $validated['client'],
            'priorite'    => $validated['priority'] ?? 'moyenne',
            'statut'      => $validated['status']    ?? 'ouvert',
            'due'         => $validated['due'],
            'user_id'     => Auth::id(),
            'projet_id'   => $validated['projet_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket créé avec succès.',
            'ticket'  => $ticket,
        ], 201);
    }

    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->with(['projet', 'tempsPasses' => function($q) {
                            $q->orderBy('date', 'desc');
                        }])
                        ->firstOrFail();

        $projets = Projet::where('user_id', Auth::id())->get();

        return view('tickets.show', compact('ticket', 'projets'));
    }

    public function edit($id)
    {
        $ticket  = Ticket::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $projets = Projet::where('user_id', Auth::id())->get();
        return view('tickets.edit', compact('ticket', 'projets'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'subject'   => 'required',
            'client'    => 'required',
            'due'       => 'required|date',
            'projet_id' => 'required|exists:projets,id',
        ]);

        $ticket->update([
            'sujet'       => $request->subject,
            'description' => $request->description,
            'client'      => $request->client,
            'priorite'    => $request->priority,
            'statut'      => $request->status,
            'due'         => $request->due,
            'projet_id'   => $request->projet_id,
        ]);

        return redirect()->route('tickets.show', $id)->with('success', 'Ticket mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $ticket   = Ticket::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $projetId = $ticket->projet_id;
        $ticket->delete();

        if ($projetId) {
            return redirect()->route('projets.show', $projetId)->with('success', 'Ticket supprimé.');
        }
        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé.');
    }

    public function updateStatut(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'statut' => 'required|in:ouvert,en cours,fermé',
        ]);

        $ticket->update(['statut' => $request->statut]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès !');
    }
}
