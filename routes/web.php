<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\TempsPasseController;
use App\Models\Ticket;
use App\Models\Projet;
use App\Models\TempsPasse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Auth
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/mdp', function() { return view('mdp'); })->name('mdp');

// Pages protégées
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [TicketController::class, 'dashboard'])->name('dashboard');

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', function() {
        return redirect()->route('projets.index')
                         ->with('info', 'Ouvrez un projet pour ajouter un ticket.');
    })->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::patch('/tickets/{id}/statut', [TicketController::class, 'updateStatut'])->name('tickets.statut');

    // Créer un ticket directement depuis un projet
    Route::get('/projets/{id}/tickets/create', [TicketController::class, 'createForProjet'])->name('projets.tickets.create');

    // Route JSON pour la modale
    Route::post('/tickets/quick-store', [TicketController::class, 'apiStore'])->name('tickets.quickstore');

    // Suivi du temps
    Route::post('/tickets/{ticketId}/temps', [TempsPasseController::class, 'store'])->name('temps.store');
    Route::delete('/temps/{id}', [TempsPasseController::class, 'destroy'])->name('temps.destroy');

    // Profil
    Route::get('/profil', function() {
        $userId = Auth::id();

        $totalTickets   = Ticket::where('user_id', $userId)->count();
        $ticketsOuverts = Ticket::where('user_id', $userId)->where('statut', 'ouvert')->count();
        $ticketsFermes  = Ticket::where('user_id', $userId)->where('statut', 'fermé')->count();
        $projetsActifs  = Projet::where('user_id', $userId)->where('statut', '!=', 'terminé')->count();

        $totalMin = TempsPasse::where('user_id', $userId)->sum('duree');
        $h   = intdiv($totalMin, 60);
        $min = $totalMin % 60;
        $tempsTotal = $h > 0 ? "{$h}h {$min}min" : "{$min}min";

        return view('profil', compact(
            'totalTickets', 'ticketsOuverts', 'ticketsFermes', 'projetsActifs', 'tempsTotal'
        ));
    })->name('profil');

    // Settings
    Route::get('/settings', function() { return view('settings'); })->name('settings');

    // Projets
    Route::get('/projets', [ProjetController::class, 'index'])->name('projets.index');
    Route::get('/projets/create', [ProjetController::class, 'create'])->name('projets.create');
    Route::post('/projets', [ProjetController::class, 'store'])->name('projets.store');
    Route::get('/projets/{id}', [ProjetController::class, 'show'])->name('projets.show');
    Route::get('/projets/{id}/edit', [ProjetController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{id}', [ProjetController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{id}', [ProjetController::class, 'destroy'])->name('projets.destroy');
    Route::patch('/projets/{id}/statut', [ProjetController::class, 'updateStatut'])->name('projets.statut');
});
