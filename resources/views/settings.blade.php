@extends('layouts.app')

@section('content')
    <a class="back" href="{{ route('dashboard') }}">← Retour</a>
    <div class="parent">
        <div class="card settings-card">
            <h2>Paramètres du compte</h2>

            <section class="settings-section">
                <h3 class="settings-title">Profil</h3>
                <div class="settings-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" class="input" value="{{ Auth::user()->username }}">
                </div>
                <div class="settings-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" class="input" placeholder="Laissez vide pour ne pas changer">
                </div>
                <button class="btn-sm">Enregistrer les modifications</button>
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
@endsection