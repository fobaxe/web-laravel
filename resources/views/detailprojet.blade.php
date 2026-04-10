<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
    <title>Détails du projet — {{ $projet->nom }}</title>
    <style>
        .tickets-projet { margin-top: 16px; }
        .tickets-projet h3 { font-size: 16px; margin: 0 0 12px; }
        .ticket-row { display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border-radius: 8px; padding: 10px 14px; margin-bottom: 8px; font-size: 13px; }
        .ticket-row a { color: #2563eb; text-decoration: none; font-weight: 600; }
        .ticket-row a:hover { text-decoration: underline; }
        .temps-badge { background: rgba(59,130,246,0.08); color: #2563eb; font-weight: 700; padding: 4px 10px; border-radius: 999px; font-size: 12px; white-space: nowrap; margin-left: 12px; }
        .total-temps { display: inline-block; background: rgba(16,185,129,0.08); color: #059669; font-weight: 700; padding: 6px 14px; border-radius: 999px; font-size: 14px; margin-top: 8px; }

        /* ── Boutons d'action ── */
        .action-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
        .btn-edit { padding: 9px 16px; border-radius: 9px; border: 1px solid #2563eb; background: transparent; color: #2563eb; font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: none; }
        .btn-edit:hover { background: #eff6ff; }
        .btn-danger { padding: 9px 16px; border-radius: 9px; border: 0; background: #ef4444; color: #fff; font-weight: 600; font-size: 13px; cursor: pointer; }
        .btn-danger:hover { background: #dc2626; }
        .btn-prendre { padding: 9px 16px; border-radius: 9px; border: 0; background: linear-gradient(90deg,#34d399,#10b981); color: #fff; font-weight: 700; font-size: 13px; cursor: pointer; }
        .btn-terminer { padding: 9px 16px; border-radius: 9px; border: 0; background: linear-gradient(90deg,#a78bfa,#7c3aed); color: #fff; font-weight: 700; font-size: 13px; cursor: pointer; }
        .btn-rouvrir { padding: 9px 16px; border-radius: 9px; border: 1px solid #6b7280; background: transparent; color: #6b7280; font-weight: 600; font-size: 13px; cursor: pointer; }
        .btn-rouvrir:hover { background: #f3f4f6; }

        /* ── Modale ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.45); backdrop-filter: blur(3px); z-index: 100; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 14px; padding: 28px 28px 22px; width: 100%; max-width: 560px; box-shadow: 0 20px 60px rgba(15,23,42,0.18); position: relative; animation: slideIn .2s ease; }
        @keyframes slideIn { from { opacity:0; transform:translateY(-12px); } to { opacity:1; transform:translateY(0); } }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .modal-header h2 { margin: 0; font-size: 18px; color: #111827; }
        .modal-close { background: none; border: none; font-size: 20px; color: #6b7280; cursor: pointer; line-height: 1; padding: 4px; }
        .modal-close:hover { color: #111827; }
        .m-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
        .m-field label { font-size: 13px; font-weight: 600; color: #6b7280; }
        .m-field input[type="text"], .m-field input[type="date"], .m-field select { padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 9px; font-size: 14px; background: #f8fafc; width: 100%; transition: border-color .15s; }
        .m-field input:focus, .m-field select:focus { outline: none; border-color: #3b82f6; background: #fff; }
        .m-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }
        .m-projet-badge { display: flex; align-items: center; gap: 8px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 9px; padding: 10px 12px; font-size: 14px; color: #1d4ed8; font-weight: 600; }
        .field-error { font-size: 12px; color: #b91c1c; background: #fee2e2; border: 1px solid #fca5a5; padding: 5px 10px; border-radius: 7px; display: none; }
        .field-error.visible { display: block; }
        .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn-primary { padding: 10px 18px; border-radius: 9px; border: 0; background: linear-gradient(90deg,#60a5fa,#2563eb); color: #fff; font-weight: 700; cursor: pointer; font-size: 14px; }
        .btn-ghost { padding: 10px 18px; border-radius: 9px; border: 1px solid #e5e7eb; background: transparent; color: #374151; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none; }
        .flash-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; font-weight: 600; padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
    </style>
</head>
<body>
<main class="container">
    <a class="back" href="{{ route('projets.index') }}">← Retour à la liste</a>

    @if(session('success'))
        <div class="flash-success">✓ {{ session('success') }}</div>
    @endif

    {{-- ── Infos projet ── --}}
    <section class="ticket-card">
        <header>
            <div>
                <h1>{{ $projet->nom }}</h1>
            </div>
            <div class="meta">
                <span>PRJ-{{ str_pad($projet->id, 3, '0', STR_PAD_LEFT) }}</span>
                @php $classeStatut = $projet->statut === 'terminé' ? 'closed' : 'open'; @endphp
                <span class="{{ $classeStatut }}">{{ ucfirst($projet->statut) }}</span>
            </div>
        </header>

        <div class="fields">
            <div class="field"><strong>Client / Sponsor :</strong> <span>{{ $projet->client }}</span></div>
            <div class="field"><strong>Priorité :</strong>
                @php
                    $classePrio = match($projet->priorite) {
                        'haute'   => 'priority-high',
                        'moyenne' => 'priority-medium',
                        default   => 'priority-low'
                    };
                @endphp
                <span class="{{ $classePrio }}">{{ ucfirst($projet->priorite) }}</span>
            </div>
            <div class="field"><strong>Date d'échéance :</strong>
                <span>{{ $projet->due ? \Carbon\Carbon::parse($projet->due)->format('d/m/Y') : '—' }}</span>
            </div>
            <div class="field"><strong>Tickets associés :</strong>
                <span>{{ $projet->tickets->count() }}</span>
            </div>
        </div>

        @if($projet->description)
        <div style="margin-top:16px;background:#f8fafc;border-radius:10px;padding:14px 16px;">
            <strong style="font-size:13px;color:#6b7280;display:block;margin-bottom:6px;">Description</strong>
            <p style="margin:0;font-size:14px;color:#374151;line-height:1.6;white-space:pre-wrap;">{{ $projet->description }}</p>
        </div>
        @endif

        <div class="total-temps">⏱ Temps total : {{ $projet->totalTemps() }}</div>

        {{-- ── Barre d'actions ── --}}
        <div class="action-bar">
            <a class="btn-edit" href="{{ route('projets.edit', $projet->id) }}">✏️ Modifier</a>

            <button id="btn-add-ticket" class="btn-primary" style="font-size:13px;padding:9px 16px;">+ Ajouter un ticket</button>

            {{-- Prise en charge / Terminer / Rouvrir --}}
            @if($projet->statut === 'planifié')
                <form action="{{ route('projets.statut', $projet->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="en-cours">
                    <button class="btn-prendre" type="submit">▶ Démarrer le projet</button>
                </form>
            @elseif($projet->statut === 'en-cours')
                <form action="{{ route('projets.statut', $projet->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="terminé">
                    <button class="btn-terminer" type="submit">✔ Marquer comme terminé</button>
                </form>
                <form action="{{ route('projets.statut', $projet->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="planifié">
                    <button class="btn-rouvrir" type="submit">↩ Remettre en planifié</button>
                </form>
            @elseif($projet->statut === 'terminé')
                <form action="{{ route('projets.statut', $projet->id) }}" method="POST" style="margin:0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="en-cours">
                    <button class="btn-rouvrir" type="submit">↩ Rouvrir</button>
                </form>
            @endif

            {{-- Supprimer --}}
            <form action="{{ route('projets.destroy', $projet->id) }}" method="POST" style="margin:0"
                  onsubmit="return confirm('Supprimer ce projet et tous ses tickets ? Cette action est irréversible.')">
                @csrf @method('DELETE')
                <button class="btn-danger" type="submit">🗑 Supprimer</button>
            </form>
        </div>
    </section>

    {{-- ── Liste des tickets ── --}}
    <section class="ticket-card tickets-projet" style="margin-top:16px;">
        <h3>🎫 Tickets du projet</h3>

        @if($projet->tickets->isEmpty())
            <p style="color:#6b7280;font-size:14px;">Aucun ticket associé à ce projet.</p>
        @else
            @foreach($projet->tickets as $ticket)
                @php
                    $classePrioT   = match($ticket->priorite) { 'haute' => 'priority-high', 'moyenne' => 'priority-medium', default => 'priority-low' };
                    $classeStatutT = $ticket->statut === 'fermé' ? 'closed' : 'open';
                @endphp
                <div class="ticket-row">
                    <div>
                        <a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->sujet }}</a>
                        <div style="margin-top:4px;display:flex;gap:6px;">
                            <span class="{{ $classeStatutT }}" style="font-size:11px;padding:2px 8px;">{{ ucfirst($ticket->statut) }}</span>
                            <span class="{{ $classePrioT }}"  style="font-size:11px;padding:2px 8px;">{{ ucfirst($ticket->priorite) }}</span>
                        </div>
                    </div>
                    <span class="temps-badge">⏱ {{ $ticket->totalTemps() }}</span>
                </div>
            @endforeach
        @endif
    </section>
</main>

{{-- ── Modale : Ajouter un ticket ── --}}
<div class="modal-overlay" id="modal-overlay">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-header">
            <h2 id="modal-title">Nouveau ticket</h2>
            <button class="modal-close" id="btn-close-modal" aria-label="Fermer">✕</button>
        </div>

        <form id="modal-ticket-form" action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <input type="hidden" name="projet_id"          value="{{ $projet->id }}">
            <input type="hidden" name="redirect_projet_id" value="{{ $projet->id }}">

            <div class="m-field">
                <label>Projet</label>
                <div class="m-projet-badge">📁 {{ $projet->nom }}</div>
            </div>
            <div class="m-field">
                <label for="m-subject">Sujet <span style="color:#ef4444">*</span></label>
                <input id="m-subject" name="subject" type="text" placeholder="Résumé bref du problème">
                <div class="field-error" id="m-subject-error">Le sujet est obligatoire.</div>
            </div>
            <div class="m-field">
                <label for="m-description">Description</label>
                <textarea id="m-description" name="description" placeholder="Décrivez le problème..."
                    style="padding:10px 12px;border:1px solid #e5e7eb;border-radius:9px;font-size:14px;background:#f8fafc;width:100%;min-height:70px;font-family:inherit;resize:vertical;"></textarea>
            </div>
            <div class="m-field">
                <label for="m-client">Client <span style="color:#ef4444">*</span></label>
                <input id="m-client" name="client" type="text" placeholder="Nom du client ou service">
                <div class="field-error" id="m-client-error">Le client est obligatoire.</div>
            </div>
            <div class="m-grid">
                <div class="m-field">
                    <label for="m-due">Date d'échéance <span style="color:#ef4444">*</span></label>
                    <input id="m-due" name="due" type="date">
                    <div class="field-error" id="m-due-error">La date est obligatoire.</div>
                </div>
                <div class="m-field">
                    <label for="m-priority">Priorité</label>
                    <select id="m-priority" name="priority">
                        <option value="basse">Basse</option>
                        <option value="moyenne" selected>Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                </div>
                <div class="m-field">
                    <label for="m-status">Statut</label>
                    <select id="m-status" name="status">
                        <option value="ouvert">Ouvert</option>
                        <option value="en cours">En cours</option>
                        <option value="fermé">Fermé</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-primary">Créer le ticket</button>
                <button type="button" class="btn-ghost" id="btn-cancel-modal">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    const overlay   = document.getElementById('modal-overlay');
    const btnAdd    = document.getElementById('btn-add-ticket');
    const btnClose  = document.getElementById('btn-close-modal');
    const btnCancel = document.getElementById('btn-cancel-modal');
    const form      = document.getElementById('modal-ticket-form');

    function openModal()  { overlay.classList.add('open'); document.getElementById('m-subject').focus(); }
    function closeModal() { overlay.classList.remove('open'); resetErrors(); }

    btnAdd.addEventListener('click', openModal);
    btnClose.addEventListener('click', closeModal);
    btnCancel.addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function resetErrors() {
        document.querySelectorAll('.field-error').forEach(el => el.classList.remove('visible'));
    }

    function validateField(inputId, errorId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (!input.value.trim()) { error.classList.add('visible'); return false; }
        error.classList.remove('visible');
        return true;
    }

    form.addEventListener('submit', function(e) {
        resetErrors();
        const ok1 = validateField('m-subject', 'm-subject-error');
        const ok2 = validateField('m-client',  'm-client-error');
        const ok3 = validateField('m-due',     'm-due-error');
        if (!ok1 || !ok2 || !ok3) e.preventDefault();
    });
</script>
</body>
</html>
