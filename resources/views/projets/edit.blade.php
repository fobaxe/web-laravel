<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/newticket.css') }}">
    <title>Modifier le projet</title>
</head>
<body>
<main class="container">
    <a class="back" href="{{ route('projets.show', $projet->id) }}">← Retour au projet</a>

    <section class="card">
        <h1>Modifier le projet <span style="color:#6b7280;font-weight:400;font-size:18px;">PRJ-{{ str_pad($projet->id, 3, '0', STR_PAD_LEFT) }}</span></h1>

        @if($errors->any())
            <div style="background:#fee2e2;border:1px solid #fca5a5;padding:12px;border-radius:8px;margin-bottom:16px;color:#991b1b;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projets.update', $projet->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div>
                    <div class="field-group">
                        <label for="nom">Nom du projet</label>
                        <input id="nom" name="nom" type="text"
                               value="{{ old('nom', $projet->nom) }}"
                               placeholder="Nom du projet">
                    </div>
                    <div class="field-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"
                                  placeholder="Décrivez le projet, ses objectifs...">{{ old('description', $projet->description) }}</textarea>
                    </div>
                    <div class="field-group">
                        <label for="client">Client</label>
                        <input id="client" name="client" type="text"
                               value="{{ old('client', $projet->client) }}"
                               placeholder="Nom du client ou service">
                    </div>
                </div>

                <aside class="right-column">
                    <div class="field-group">
                        <label for="statut">Statut</label>
                        <select id="statut" name="statut">
                            <option value="planifié"  {{ old('statut', $projet->statut) === 'planifié'  ? 'selected' : '' }}>Planifié</option>
                            <option value="en-cours"  {{ old('statut', $projet->statut) === 'en-cours'  ? 'selected' : '' }}>En cours</option>
                            <option value="terminé"   {{ old('statut', $projet->statut) === 'terminé'   ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="priorite">Priorité</label>
                        <select id="priorite" name="priorite">
                            <option value="basse"   {{ old('priorite', $projet->priorite) === 'basse'   ? 'selected' : '' }}>Basse</option>
                            <option value="moyenne" {{ old('priorite', $projet->priorite) === 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                            <option value="haute"   {{ old('priorite', $projet->priorite) === 'haute'   ? 'selected' : '' }}>Haute</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="due">Date d'échéance</label>
                        <input id="due" name="due" type="date"
                               value="{{ old('due', $projet->due ? \Carbon\Carbon::parse($projet->due)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-actions">
                        <button class="btn-sm" type="submit">💾 Enregistrer</button>
                        <a class="btn-cancel" href="{{ route('projets.show', $projet->id) }}">Annuler</a>
                    </div>
                </aside>
            </div>
        </form>
    </section>
</main>
</body>
</html>
