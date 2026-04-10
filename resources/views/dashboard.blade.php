@extends('layouts.app')

@section('content')
    <div class="main-header">
        <h2>Tickets récents</h2>
    </div>

    <div id="titrefiltre"><p>Filtre:</p></div>
    <section class="Filtre">
        <div class="filtre-status">
            <p id="statut">Statut</p>
            <div class="filtre-btn">
                <a class="btn-filtre" href="#">Ouvert</a>
                <a class="btn-filtre" href="#">En cours</a>
                <a class="btn-filtre" href="#">Fermé</a>
                <a class="btn-filtre" href="#">Tous</a>
            </div>
        </div>
        <div class="filtre-priorite">
            <p>Priorité</p>
            <div class="filtre-btn">
                <a class="btn-filtre1" href="#">Haute</a>
                <a class="btn-filtre1" href="#">Moyenne</a>
                <a class="btn-filtre1" href="#">Basse</a>
                <a class="btn-filtre1" href="#">Toutes</a>
            </div>
        </div>
    </section>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Sujet</th>
                    <th>Client</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                    @php
                        $classePrio = match($ticket->priorite) {
                            'haute' => 'priority-high',
                            'moyenne' => 'priority-medium',
                            default => 'priority-low'
                        };
                        $classeStatut = $ticket->statut == 'fermé' ? 'status-closed' : 'status-open';
                    @endphp
                    <tr data-statut="{{ $ticket->statut }}" data-priorite="{{ $ticket->priorite }}">
                        <td class="{{ $classeStatut }} status">
                            <a class="cell-link" href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->sujet }}</a>
                        </td>
                        <td>
                            <a class="cell-link" href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->client }}</a>
                        </td>
                        <td class="{{ $classePrio }} priority">
                            <a class="cell-link" href="{{ route('tickets.show', $ticket->id) }}">{{ ucfirst($ticket->priorite) }}</a>
                        </td>
                        <td class="{{ $classeStatut }} status">
                            <a class="cell-link" href="{{ route('tickets.show', $ticket->id) }}">{{ ucfirst($ticket->statut) }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="{{ asset('js/script2.js') }}"></script>
@endsection
