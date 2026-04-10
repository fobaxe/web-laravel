<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>TicketFlow</title>
    <style>
        .btn-logout {
            background: transparent;
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-logout:hover {
            background: #fee2e2;
            border-color: #ef4444;
            color: #ef4444;
        }
        /* ── Overlay de la modale ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: transparent;
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open {
            display: flex;
        }

        /* ── Boîte de la modale ── */
        .modal {
            background: #fff;
            border-radius: 14px;
            padding: 28px 32px;
            width: 100%;
            max-width: 560px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.18);
            animation: modal-in 0.2s ease;
        }
        @keyframes modal-in {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 18px;
            color: #0f172a;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #6b7280;
            line-height: 1;
        }
        .modal-close:hover { color: #0f172a; }

        /* ── Grille du formulaire ── */
        .modal-form .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .modal-form .field-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .modal-form label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
        }
        .modal-form input,
        .modal-form select {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
            color: #0f172a;
        }
        .modal-form input:focus,
        .modal-form select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        /* ── Champ sujet pleine largeur ── */
        .modal-form .full-width {
            grid-column: 1 / -1;
        }

        /* ── Messages d'erreur ── */
        .modal-form .field-error {
            font-size: 12px;
            color: #b23b2c;
            background: #ffe7df;
            border: 1px solid #f2c2b8;
            padding: 5px 8px;
            border-radius: 6px;
            display: none;
        }
        .modal-form .field-error.visible { display: block; }

        /* ── Actions ── */
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 22px;
        }
        .btn-modal-submit {
            padding: 10px 20px;
            border-radius: 8px;
            border: 0;
            background: linear-gradient(90deg, #60a5fa, #2563eb);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-modal-submit:hover { filter: brightness(1.06); }
        .btn-modal-cancel {
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: transparent;
            color: #374151;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
        }

        /* ── Toast succès ── */
        .modal-toast {
            display: none;
            margin-top: 14px;
            padding: 10px 14px;
            background: #1f6f54;
            color: #fff;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }
        .modal-toast.visible { display: block; }

        @media (max-width: 540px) {
            .modal { padding: 20px 16px; margin: 0 12px; }
            .modal-form .form-grid { grid-template-columns: 1fr; }
            .modal-form .full-width { grid-column: 1; }
        }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('dashboard') }}">
            <img class="logo" src="{{ asset('assets/logo.png') }}" alt="logo">
        </a>
        <button type="button" class="create-ticket" onclick="window.location.href='{{ route('projets.create') }}'">Créer un projet</button>
        {{-- Ce bouton ouvre la modale au lieu de rediriger --}}
        <button type="button" class="create-ticket" id="btn-open-modal">Créer un ticket</button>
        <button type="button" class="login" onclick="window.location.href='{{ route('profil') }}'">Mon compte</button>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="btn-logout">Déconnexion</button>
        </form>
    </header>

    <div class="parent">
        <aside class="sidebar">
            <h3>Navigation</h3>
            <div class="side-buttons">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <a href="{{ route('tickets.index') }}">Liste des tickets</a>
                <a href="{{ route('projets.index') }}">Liste des projets</a>
            </div>
            <a href="{{ route('settings') }}">
                <img class="settings" src="{{ asset('assets/parametres.png') }}" alt="Paramètres">
            </a>
        </aside>

        <section class="main-content">
            @yield('content')
        </section>
    </div>

    {{-- ══════════════════════════════════════════
         MODALE — Création rapide de ticket via API
         ══════════════════════════════════════════ --}}
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">

            <div class="modal-header">
                <h2 id="modal-title">Créer un ticket</h2>
                <button class="modal-close" id="btn-close-modal" aria-label="Fermer">✕</button>
            </div>

            <form class="modal-form" id="modal-ticket-form" novalidate>

                <div class="form-grid">
                    {{-- Sujet — pleine largeur --}}
                    <div class="field-group full-width">
                        <label for="m-subject">Sujet</label>
                        <input id="m-subject" type="text" placeholder="Résumé bref du problème">
                        <span class="field-error" id="m-subject-error">Le sujet est obligatoire</span>
                    </div>

                    {{-- Description — pleine largeur --}}
                    <div class="field-group full-width">
                        <label for="m-description">Description</label>
                        <textarea id="m-description" placeholder="Décrivez le problème en détail..." style="padding:10px 12px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;background:#fff;color:#0f172a;min-height:80px;resize:vertical;font-family:inherit;"></textarea>
                    </div>

                    {{-- Client — pleine largeur --}}
                    <div class="field-group full-width">
                        <label for="m-client">Client</label>
                        <input id="m-client" type="text" placeholder="Nom du client ou service">
                        <span class="field-error" id="m-client-error">Le client est obligatoire</span>
                    </div>

                    {{-- Projet associé — pleine largeur --}}
                    <div class="field-group full-width">
                        <label for="m-projet">Projet associé <span style="color:#ef4444">*</span></label>
                        <select id="m-projet">
                            <option value="">-- Sélectionner un projet --</option>
                            @foreach(\App\Models\Projet::where('user_id', Auth::id())->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->nom }}</option>
                            @endforeach
                        </select>
                        <span class="field-error" id="m-projet-error">Le projet est obligatoire</span>
                    </div>

                    {{-- Date d'échéance --}}
                    <div class="field-group">
                        <label for="m-due">Date d'échéance</label>
                        <input id="m-due" type="date">
                        <span class="field-error" id="m-due-error">La date est obligatoire</span>
                    </div>

                    {{-- Priorité --}}
                    <div class="field-group">
                        <label for="m-priority">Priorité</label>
                        <select id="m-priority">
                            <option value="basse">Basse</option>
                            <option value="moyenne" selected>Moyenne</option>
                            <option value="haute">Haute</option>
                        </select>
                    </div>

                    {{-- Statut --}}
                    <div class="field-group">
                        <label for="m-status">Statut</label>
                        <select id="m-status">
                            <option value="ouvert">Ouvert</option>
                            <option value="en cours">En cours</option>
                            <option value="fermé">Fermé</option>
                        </select>
                    </div>
                </div>

                <div class="modal-toast" id="modal-toast">✓ Ticket créé avec succès !</div>

                <div class="modal-actions">
                    <button type="button" class="btn-modal-cancel" id="btn-cancel-modal">Annuler</button>
                    <button type="submit" class="btn-modal-submit">Créer le ticket</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/script-modal.js') }}"></script>
</body>
</html>
