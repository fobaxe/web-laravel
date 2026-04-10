<?php
namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjetController extends Controller
{
    public function index()
    {
        $projets = Projet::where('user_id', Auth::id())->withCount('tickets')->get();
        return view('projets.index', compact('projets'));
    }

    public function create()
    {
        return view('createproject');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'    => 'required',
            'client' => 'required',
            'due'    => 'required|date',
        ]);

        Projet::create([
            'nom'         => $request->nom,
            'description' => $request->description,
            'client'      => $request->client,
            'priorite'    => $request->priorite,
            'statut'      => $request->statut,
            'due'         => $request->due,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('projets.index')->with('success', 'Projet créé avec succès !');
    }

    public function show($id)
    {
        $projet = Projet::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->with(['tickets.tempsPasses'])
                        ->firstOrFail();

        return view('detailprojet', compact('projet'));
    }

    public function edit($id)
    {
        $projet = Projet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('projets.edit', compact('projet'));
    }

    public function update(Request $request, $id)
    {
        $projet = Projet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nom'    => 'required',
            'client' => 'required',
            'due'    => 'required|date',
        ]);

        $projet->update([
            'nom'         => $request->nom,
            'description' => $request->description,
            'client'      => $request->client,
            'priorite'    => $request->priorite,
            'statut'      => $request->statut,
            'due'         => $request->due,
        ]);

        return redirect()->route('projets.show', $id)->with('success', 'Projet mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $projet = Projet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $projet->delete();

        return redirect()->route('projets.index')->with('success', 'Projet supprimé.');
    }

    public function updateStatut(Request $request, $id)
    {
        $projet = Projet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'statut' => 'required|in:planifié,en-cours,terminé',
        ]);

        $projet->update(['statut' => $request->statut]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès !');
    }
}
