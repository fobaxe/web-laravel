@extends('layouts.app')

@section('content')
    <table class="tickets-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Sujet</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Date d'échéance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                @php
                    $classePrio = match($ticket->priorite) {
                        'haute' => 'prio-high',
                        'moyenne' => 'prio-med',
                        default => 'prio-low'
                    };
                    $classeStatut = $ticket->statut == 'fermé' ? 'closed' : 'open';
                    $dateFormatee = $ticket->due ? \Carbon\Carbon::parse($ticket->due)->format('d/m/Y') : '—';
                @endphp
                <tr>
                    <td>{{ $ticket->client }}</td>
                    <td>{{ $ticket->sujet }}</td>
                    <td><span class="{{ $classePrio }}">{{ $ticket->priorite }}</span></td>
                    <td><span class="{{ $classeStatut }}">{{ $ticket->statut }}</span></td>
                    <td>{{ $dateFormatee }}</td>
                    <td><a class="btn-sm" href="{{ route('tickets.show', $ticket->id) }}">Voir le ticket</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection