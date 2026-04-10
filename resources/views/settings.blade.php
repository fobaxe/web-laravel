@extends('layouts.app')

@section('content')
    <a class="back" href="{{ route('dashboard') }}">← Retour</a>
    <div class="parent">
        <div class="card settings-card">
            <h2>Paramètres du compte</h2>

            {{-- Message de succès / erreur --}}
            <div id="settings-toast" class="settings-toast"></div>

            <section class="settings-section">
                <h3 class="settings-title">Profil</h3>
                <form id="settings-form" novalidate>
                    <div class="settings-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" class="input" value="{{ Auth::user()->username }}">
                    </div>
                    <div class="settings-group">
                        <label for="current_password">Mot de passe actuel <span style="color:#ef4444">*</span></label>
                        <input type="password" id="current_password" class="input" placeholder="Requis pour enregistrer">
                        <span class="settings-field-error" id="current_password-error"></span>
                    </div>
                    <div class="settings-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" class="input" placeholder="Laissez vide pour ne pas changer">
                        <span class="settings-field-error" id="password-error"></span>
                    </div>
                    <div class="settings-group">
                        <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="password_confirmation" class="input" placeholder="Confirmez le nouveau mot de passe">
                        <span class="settings-field-error" id="password_confirmation-error"></span>
                    </div>
                    <button type="submit" class="btn-sm" id="btn-save-settings">Enregistrer les modifications</button>
                </form>
            </section>

            <section class="settings-section">
                <h3 class="settings-title">Préférences</h3>
                <div class="settings-group">
                    <label for="language">Langue</label>
                    <select id="language" class="input">
                        <option value="fr">Français</option>
                        <option value="en">English</option>
                    </select>
                </div>
                <div class="settings-group">
                    <label for="theme">Thème</label>
                    <select id="theme" class="input">
                        <option value="light">Clair</option>
                        <option value="dark">Sombre</option>
                        <option value="auto">Automatique</option>
                    </select>
                </div>
            </section>

            <section class="settings-section danger-zone">
                <button class="btn-danger">Supprimer mon compte</button>
            </section>
        </div>
    </div>

    <script>
    // Gestion du formulaire de mise à jour du profil (settings).
    document.getElementById('settings-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const toast = document.getElementById('settings-toast');
        const btn   = document.getElementById('btn-save-settings');

        // Nettoie les erreurs precedentes.
        document.querySelectorAll('.settings-field-error').forEach(el => {
            el.textContent = '';
            el.classList.remove('visible');
        });
        toast.className = 'settings-toast';
        toast.textContent = '';

        // Verifie que le mot de passe actuel est renseigne.
        const currentPw = document.getElementById('current_password').value;
        if (!currentPw.trim()) {
            const err = document.getElementById('current_password-error');
            err.textContent = 'Le mot de passe actuel est obligatoire.';
            err.classList.add('visible');
            return;
        }

        // Recupere le token CSRF de la balise <meta>.
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Construit la charge utile.
        const payload = {
            username: document.getElementById('username').value.trim(),
            current_password: currentPw,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
        };

        btn.disabled = true;
        btn.textContent = 'Enregistrement…';

        try {
            const response = await fetch('{{ route("settings.update") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Affiche le message de succes.
                toast.textContent = '✓ ' + data.message;
                toast.classList.add('visible', 'success');

                // Vide les champs de mot de passe apres succes.
                document.getElementById('current_password').value = '';
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
            } else {
                // Affiche les erreurs de validation renvoyees par Laravel.
                if (data.errors) {
                    for (const [field, messages] of Object.entries(data.errors)) {
                        const errEl = document.getElementById(field + '-error');
                        if (errEl) {
                            errEl.textContent = messages[0];
                            errEl.classList.add('visible');
                        }
                    }
                }
                toast.textContent = '✗ Veuillez corriger les erreurs.';
                toast.classList.add('visible', 'error');
            }
        } catch (err) {
            console.error('Erreur réseau :', err);
            toast.textContent = '✗ Impossible de contacter le serveur.';
            toast.classList.add('visible', 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Enregistrer les modifications';
        }
    });
    </script>
@endsection