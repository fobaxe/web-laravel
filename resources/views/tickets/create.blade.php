<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/newticket.css') }}">
    <title>Créer un ticket</title>
</head>
<body>
    <main class="container">
        {{-- Retour vers le projet si on vient de là, sinon vers la liste des tickets --}}
        @if(isset($projet))
            <a class="back" href="{{ route('projets.show', $projet->id) }}">← Retour au projet : {{ $projet->nom }}</a>
        @else
            <a class="back" href="{{ route('tickets.index') }}">← Retour</a>
        @endif

        <section class="card">
            <h1>Créer un ticket</h1>

            @if($projets->isEmpty())
                <div style="background:#fff3cd;border:1px solid #ffc107;padding:12px;border-radius:8px;margin-bottom:16px;color:#856404;">
                    ⚠️ Vous devez d'abord <a href="{{ route('projets.create') }}">créer un projet</a> avant de pouvoir créer un ticket.
                </div>
            @endif

            @if ($errors->any())
                <div style="background:#fee2e2;border:1px solid #fca5a5;padding:12px;border-radius:8px;margin-bottom:16px;color:#991b1b;">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="create-ticket-form" action="{{ route('tickets.store') }}" method="POST">
                @csrf

                {{-- Champ caché pour retourner vers le projet après création --}}
                @if(isset($projet))
                    <input type="hidden" name="redirect_projet_id" value="{{ $projet->id }}">
                @endif

                <div class="form-grid">
                    <div>
                        <div class="field-group">
                            <label for="subject">Sujet</label>
                            <input id="subject" name="subject" type="text"
                                   placeholder="Résumé bref du problème"
                                   value="{{ old('subject') }}">
                            <div id="subject-error" class="message-erreur titanic">Le sujet est obligatoire</div>
                        </div>
                        <div class="field-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Décrivez le problème en détail...">{{ old('description') }}</textarea>
                        </div>
                        <div class="field-group">
                            <label for="client">Client</label>
                            <input id="client" name="client" type="text"
                                   placeholder="Nom du client ou service"
                                   value="{{ old('client') }}">
                            <div id="client-error" class="message-erreur titanic">Le nom est obligatoire</div>
                        </div>
                        <div class="field-group">
                            <label>Projet associé <span style="color:#ef4444;">*</span></label>

                            @if(isset($projet))
                                {{-- Projet verrouillé : vient de la page projet --}}
                                <input type="hidden" name="projet_id" value="{{ $projet->id }}">
                                <div style="
                                    display:flex;align-items:center;gap:8px;
                                    background:#eff6ff;border:1px solid #bfdbfe;
                                    border-radius:8px;padding:10px 14px;font-size:13px;
                                    color:#1d4ed8;font-weight:600;">
                                    📁 {{ $projet->nom }}
                                    <a href="{{ route('projets.show', $projet->id) }}"
                                       style="margin-left:auto;font-size:11px;color:#6b7280;font-weight:400;text-decoration:none;">
                                        Voir le projet →
                                    </a>
                                </div>
                            @else
                                <select id="projet_id" name="projet_id" {{ $projets->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">-- Sélectionner un projet --</option>
                                    @foreach($projets as $p)
                                        <option value="{{ $p->id }}" {{ old('projet_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="projet-error" class="message-erreur titanic">Le projet est obligatoire</div>
                            @endif
                        </div>
                    </div>

                    <aside class="right-column">
                        <div class="field-group">
                            <label for="status">Statut</label>
                            <select id="status" name="status">
                                <option value="ouvert">Ouvert</option>
                                <option value="en cours">En cours</option>
                                <option value="fermé">Fermé</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="priority">Priorité</label>
                            <select id="priority" name="priority">
                                <option value="basse">Basse</option>
                                <option value="moyenne" selected>Moyenne</option>
                                <option value="haute">Haute</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="due">Date d'échéance</label>
                            <input id="due" name="due" type="date" value="{{ old('due') }}">
                            <div id="due-error" class="message-erreur titanic">La date d'échéance est obligatoire</div>
                        </div>
                        <div class="form-actions">
                            <button class="btn-sm" type="submit"
                                {{ $projets->isEmpty() ? 'disabled' : '' }}
                                style="{{ $projets->isEmpty() ? 'opacity:0.5;cursor:not-allowed;' : '' }}">
                                Créer le ticket
                            </button>
                            @if(isset($projet))
                                <a class="btn-cancel" href="{{ route('projets.show', $projet->id) }}">Annuler</a>
                            @else
                                <a class="btn-cancel" href="{{ route('tickets.index') }}">Annuler</a>
                            @endif
                        </div>
                    </aside>
                </div>
            </form>
        </section>
        <div id="success" class="toast titanic">Ticket créé avec succès.</div>
    </main>
    <script src="{{ asset('js/script1.js') }}"></script>
</body>
</html>
