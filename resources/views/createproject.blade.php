<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/newticket.css') }}">
    <title>Créer un projet</title>
</head>
<body>
    <main class="container">
        <a class="back" href="{{ route('dashboard') }}">← Retour</a>
        <section class="card">
            <h1>Créer un projet</h1>
            <form id="create-project-form" action="{{ route('projets.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div>
                        <div class="field-group">
                            <label for="nom">Nom du projet</label>
                            <input id="nom" name="nom" type="text" placeholder="Nom du projet">
                            <div id="nom-error" class="message-erreur titanic">Le nom est obligatoire</div>
                        </div>
                        <div class="field-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Décrivez le projet, ses objectifs..."></textarea>
                        </div>
                        <div class="field-group">
                            <label for="client">Client</label>
                            <input id="client" name="client" type="text" placeholder="Nom du client ou service">
                            <div id="client-error" class="message-erreur titanic">Le client est obligatoire</div>
                        </div>
                    </div>

                    <aside class="right-column">
                        <div class="field-group">
                            <label for="statut">Statut</label>
                            <select id="statut" name="statut">
                                <option value="en-cours">En cours</option>
                                <option value="planifié">Planifié</option>
                                <option value="terminé">Terminé</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="priorite">Priorité</label>
                            <select id="priorite" name="priorite">
                                <option value="basse">Basse</option>
                                <option value="moyenne" selected>Moyenne</option>
                                <option value="haute">Haute</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="due">Date d'échéance</label>
                            <input id="due" name="due" type="date">
                            <div id="due-error" class="message-erreur titanic">La date d'échéance est obligatoire</div>
                        </div>

                        <div class="form-actions">
                            <button class="btn-sm" type="submit">Créer le projet</button>
                            <a class="btn-cancel" href="{{ route('dashboard') }}">Annuler</a>
                        </div>
                    </aside>
                </div>
            </form>
        </section>
        <div id="success" class="toast titanic">
            Projet créé avec succès.
        </div>
    </main>
    <script src="{{ asset('js/script3.js') }}"></script>
</body>
</html>