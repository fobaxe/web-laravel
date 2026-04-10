<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
    <title>Détails du ticket</title>
    <style>
        .temps-section { margin-top: 24px; }
        .temps-section h3 { margin: 0 0 12px; color: #111827; font-size: 16px; }
        .temps-form {
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px;
            display: grid;
            grid-template-columns: 1fr 1fr 2fr auto;
            gap: 10px;
            align-items: end;
            margin-bottom: 16px;
        }
        .temps-form label { font-size: 12px; font-weight: 600; color: #6b7280; display: block; margin-bottom: 4px; }
        .temps-form input { width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; }
        .btn-temps { padding: 9px 14px; border-radius: 8px; border: 0; background: linear-gradient(90deg,#60a5fa,#3b82f6); color: #fff; font-weight: 700; cursor: pointer; white-space: nowrap; font-size: 13px; }
        .temps-total { display: inline-block; background: rgba(59,130,246,0.08); color: #2563eb; font-weight: 700; padding: 6px 12px; border-radius: 999px; font-size: 14px; margin-bottom: 12px; }
        .temps-list { display: flex; flex-direction: column; gap: 8px; }
        .temps-item { background: #f8fafc; border-radius: 8px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #374151; }
        .temps-item .temps-meta { color: #6b7280; font-size: 12px; margin-top: 2px; }
        .temps-duree { font-weight: 700; color: #2563eb; }
        .btn-delete { background: none; border: none; color: #ef4444; cursor: pointer; font-size: 16px; padding: 2px 6px; }
        .projet-badge { display: inline-block; background: rgba(59,130,246,0.08); color: #2563eb; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 999px; text-decoration: none; }

        /* ── Boutons d'action ── */
        .action-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
        .btn-edit { padding: 9px 16px; border-radius: 9px; border: 1px solid #2563eb; background: transparent; color: #2563eb; font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: none; }
        .btn-edit:hover { background: #eff6ff; }
        .btn-danger { padding: 9px 16px; border-radius: 9px; border: 0; background: #ef4444; color: #fff; font-weight: 600; font-size: 13px; cursor: pointer; }
        .btn-danger:hover { background: #dc2626; }
        .btn-prendre { padding: 9px 16px; border-radius: 9px; border: 0; background: linear-gradient(90deg,#34d399,#10b981); color: #fff; font-weight: 700; font-size: 13px; cursor: pointer; }
        .btn-prendre:hover { filter: brightness(1.05); }
        .btn-terminer { padding: 9px 16px; border-radius: 9px; border: 0; background: linear-gradient(90deg,#a78bfa,#7c3aed); color: #fff; font-weight: 700; font-size: 13px; cursor: pointer; }
        .btn-terminer:hover { filter: brightness(1.05); }
        .btn-rouvrir { padding: 9px 16px; border-radius: 9px; border: 1px solid #6b7280; background: transparent; color: #6b7280; font-weight: 600; font-size: 13px; cursor: pointer; }
        .btn-rouvrir:hover { background: #f3f4f6; }

        @media(max-width:640px){
            .temps-form { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
<main class="container">
    <a class="back" href="{{ route('tickets.index') }}">← Retour à la liste</a>

    @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;padding:10px 14px;border-radius:8px;margin-bottom:12px;color:#065f46;font-weight:600;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <section class="ticket-card">
        <header>
            <div>
                <h1>{{ $ticket->sujet }}</h1>
                @if($ticket->projet)
                    <a class="projet-badge" href="{{ route('projets.show', $ticket->projet->id) }}">
                        📁 {{ $ticket->projet->nom }}
                    </a>
                @endif
            </div>
            <div class="meta">
                <span>#{{ $ticket->id }}</span>
                @php $classeStatut = $ticket->statut === 'fermé' ? 'closed' : 'open'; @endphp
                <span class="{{ $classeStatut }}">{{ ucfirst($ticket->statut) }}</span>
            </div>
        </header>

        <div class="fields">
            <div class="field"><strong>Client :</strong> <span>{{ $ticket->client }}</span></div>
            <div class="field"><strong>Priorité :</strong>
                @php
                    $classePrio = match($ticket->priorite) {
                        'haute'   => 'priority-high',
                        'moyenne' => 'priority-medium',
                        default   => 'priority-low'
                    };
                @endphp
                <span class="{{ $classePrio }}">{{ ucfirst($ticket->priorite) }}</span>
            </div>
            <div class="field"><strong>Date d'échéance :</strong>
                <span>{{ $ticket->due ? \Carbon\Carbon::parse($ticket->due)->format('d/m/Y') : '—' }}</span>
            </div>
            <div class="field"><strong>Temps total :</strong>
                <span class="temps-duree">{{ $ticket->totalTemps() }}</span>
            </div>
        </div>

        @if($ticket->description)
        <div style="margin-top:16px;background:#f8fafc;border-radius:10px;padding:14px 16px;">
            <strong style="font-size:13px;color:#6b7280;display:block;margin-bottom:6px;">Description</strong>
            <p style="margin:0;font-size:14px;color:#374151;line-height:1.6;white-space:pre-wrap;">{{ $ticket->description }}</p>
        </div>
        @endif

        {{-- ── Barre d'actions ── --}}
        <div class="action-bar">
            {{-- Modifier --}}
            <a class="btn-edit" href="{{ route('tickets.edit', $ticket->id) }}">✏️ Modifier</a>

            {{-- Prise en charge / Terminer / Rouvrir selon le statut actuel --}}
            @if($ticket->statut === 'ouvert')
                <form action="{{ route('tickets.statut', $ticket->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="en cours">
                    <button class="btn-prendre" type="submit">▶ Prendre en charge</button>
                </form>
            @elseif($ticket->statut === 'en cours')
                <form action="{{ route('tickets.statut', $ticket->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="fermé">
                    <button class="btn-terminer" type="submit">✔ Marquer comme terminé</button>
                </form>
                <form action="{{ route('tickets.statut', $ticket->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="ouvert">
                    <button class="btn-rouvrir" type="submit">↩ Rouvrir</button>
                </form>
            @elseif($ticket->statut === 'fermé')
                <form action="{{ route('tickets.statut', $ticket->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="ouvert">
                    <button class="btn-rouvrir" type="submit">↩ Rouvrir</button>
                </form>
            @endif

            {{-- Supprimer --}}
            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="margin:0"
                  onsubmit="return confirm('Supprimer ce ticket ? Cette action est irréversible.')">
                @csrf @method('DELETE')
                <button class="btn-danger" type="submit">🗑 Supprimer</button>
            </form>
        </div>
    </section>

    {{-- ── Suivi du temps ── --}}
    <section class="ticket-card temps-section" style="margin-top:16px;">
        <h3>⏱ Suivi du temps</h3>

        <form action="{{ route('temps.store', $ticket->id) }}" method="POST">
            @csrf
            <div class="temps-form">
                <div>
                    <label>Date</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label>Durée (minutes)</label>
                    <input type="number" name="duree" min="1" placeholder="ex: 90" required>
                </div>
                <div>
                    <label>Commentaire (optionnel)</label>
                    <input type="text" name="commentaire" placeholder="Ce que vous avez fait...">
                </div>
                <div>
                    <button class="btn-temps" type="submit">+ Ajouter</button>
                </div>
            </div>
        </form>

        @if($ticket->tempsPasses->isEmpty())
            <p style="color:#6b7280;font-size:14px;">Aucun temps enregistré pour ce ticket.</p>
        @else
            <div class="temps-total">Total : {{ $ticket->totalTemps() }}</div>
            <div class="temps-list">
                @foreach($ticket->tempsPasses as $temps)
                    <div class="temps-item">
                        <div>
                            <div><strong class="temps-duree">{{ $temps->dureeFormatee() }}</strong>
                                @if($temps->commentaire) — {{ $temps->commentaire }} @endif
                            </div>
                            <div class="temps-meta">{{ \Carbon\Carbon::parse($temps->date)->format('d/m/Y') }}</div>
                        </div>
                        <form action="{{ route('temps.destroy', $temps->id) }}" method="POST" style="margin:0">
                            @csrf @method('DELETE')
                            <button class="btn-delete" type="submit" title="Supprimer">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</main>
</body>
</html>
