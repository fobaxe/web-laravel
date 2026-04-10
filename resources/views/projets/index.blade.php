@extends('layouts.app')

@section('content')
    <div class="main-header">
        <h2>Liste des projets</h2>
    </div>

    <div class="card">
        <table class="tickets-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Client</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Date d'échéance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projets as $projet)
                    @php
                        $classePrio = match($projet->priorite) {
                            'haute'   => 'prio-high',
                            'moyenne' => 'prio-med',
                            default   => 'prio-low'
                        };
                        $classeStatut = $projet->statut == 'terminé' ? 'closed' : 'open';
                        $dateFormatee = $projet->due
                            ? \Carbon\Carbon::parse($projet->due)->format('d/m/Y')
                            : '—';
                    @endphp
                    <tr>
                        <td>{{ $projet->nom }}</td>
                        <td>{{ $projet->client }}</td>
                        <td><span class="{{ $classePrio }}">{{ ucfirst($projet->priorite) }}</span></td>
                        <td><span class="{{ $classeStatut }}">{{ ucfirst($projet->statut) }}</span></td>
                        <td>{{ $dateFormatee }}</td>
                        <td><a class="btn-sm" href="{{ route('projets.show', $projet->id) }}">Voir le projet</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection