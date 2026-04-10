<?php
namespace App\Http\Controllers;

use App\Models\TempsPasse;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TempsPasseController extends Controller
{
    public function store(Request $request, $ticketId)
    {
        $request->validate([
            'date'        => 'required|date',
            'duree'       => 'required|integer|min:1',
            'commentaire' => 'nullable|string|max:500',
        ]);

        // Vérifier que le ticket appartient à l'utilisateur
        $ticket = Ticket::where('id', $ticketId)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        TempsPasse::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => Auth::id(),
            'date'        => $request->date,
            'duree'       => $request->duree,
            'commentaire' => $request->commentaire,
        ]);

        return redirect()->route('tickets.show', $ticketId)
                         ->with('success', 'Temps ajouté avec succès !');
    }

    public function destroy($id)
    {
        $temps = TempsPasse::where('id', $id)
                           ->where('user_id', Auth::id())
                           ->firstOrFail();

        $ticketId = $temps->ticket_id;
        $temps->delete();

        return redirect()->route('tickets.show', $ticketId)
                         ->with('success', 'Entrée supprimée.');
    }
}
