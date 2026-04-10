@extends('layouts.app')

@section('content')
    <a class="back" href="{{ route('dashboard') }}">← Retour</a>
    <div class="parent">
        <div class="card profile-card">
            <div class="profile-header">
                <div class="profile-info">
                    <h2>{{ Auth::user()->username }}</h2>
                </div>
            </div>

            <div class="stat-grid">
                <div class="card stat-card">
                    <h3>Total tickets</h3>
                    <div class="stat-number">{{ $totalTickets }}</div>
                </div>
                <div class="card stat-card">
                    <h3>Ouverts</h3>
                    <div class="stat-number">{{ $ticketsOuverts }}</div>
                </div>
                <div class="card stat-card">
                    <h3>Fermés</h3>
                    <div class="stat-number">{{ $ticketsFermes }}</div>
                </div>
                <div class="card stat-card">
                    <h3>Projets actifs</h3>
                    <div class="stat-number">{{ $projetsActifs }}</div>
                </div>
                <div class="card stat-card">
                    <h3>Temps total</h3>
                    <div class="stat-number" style="font-size:22px;">{{ $tempsTotal }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
