<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/newticket.css') }}">
    <title>Modifier le ticket</title>
</head>
<body>
<main class="container">
    <a class="back" href="{{ route('tickets.show', $ticket->id) }}">← Retour au ticket</a>

    <section class="card">
        <h1>Modifier le ticket <span style="color:#6b7280;font-weight:400;font-size:18px;">#{{ $ticket->id }}</span></h1>

        @if($errors->any())
            <div style="background:#fee2e2;border:1px solid #fca5a5;padding:12px;border-radius:8px;margin-bottom:16px;color:#991b1b;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div>
                    <div class="field-group">
                        <label for="subject">Sujet</label>
                        <input id="subject" name="subject" type="text"
                               value="{{ old('subject', $ticket->sujet) }}"
                               placeholder="Résumé bref du problème">
                    </div>
                    <div class="field-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"
                                  placeholder="Décrivez le problème en détail...">{{ old('description', $ticket->description) }}</textarea>
                    </div>
                    <div class="field-group">
                        <label for="client">Client</label>
                        <input id="client" name="client" type="text"
                               value="{{ old('client', $ticket->client) }}"
                               placeholder="Nom du client ou service">
                    </div>
                    <div class="field-group">
                        <label for="projet_id">Projet associé <span style="color:#ef4444;">*</span></label>
                        <select id="projet_id" name="projet_id">
                            <option value="">-- Sélectionner un projet --</option>
                            @foreach($projets as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('projet_id', $ticket->projet_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <aside class="right-column">
                    <div class="field-group">
                        <label for="status">Statut</label>
                        <select id="status" name="status">
                            <option value="ouvert"   {{ old('status', $ticket->statut) === 'ouvert'   ? 'selected' : '' }}>Ouvert</option>
                            <option value="en cours" {{ old('status', $ticket->statut) === 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="fermé"    {{ old('status', $ticket->statut) === 'fermé'    ? 'selected' : '' }}>Fermé</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="priority">Priorité</label>
                        <select id="priority" name="priority">
                            <option value="basse"   {{ old('priority', $ticket->priorite) === 'basse'   ? 'selected' : '' }}>Basse</option>
                            <option value="moyenne" {{ old('priority', $ticket->priorite) === 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                            <option value="haute"   {{ old('priority', $ticket->priorite) === 'haute'   ? 'selected' : '' }}>Haute</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="due">Date d'échéance</label>
                        <input id="due" name="due" type="date"
                               value="{{ old('due', $ticket->due ? \Carbon\Carbon::parse($ticket->due)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-actions">
                        <button class="btn-sm" type="submit">💾 Enregistrer</button>
                        <a class="btn-cancel" href="{{ route('tickets.show', $ticket->id) }}">Annuler</a>
                    </div>
                </aside>
            </div>
        </form>
    </section>
</main>
</body>
</html>
