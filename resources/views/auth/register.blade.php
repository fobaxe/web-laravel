<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/log.css') }}">
    <title>Créer un compte</title>
</head>
<body>
    <header>
        <a href="{{ route('login') }}">
            <img class="logo" src="{{ asset('assets/logo.png') }}" alt="Logo du site">
        </a>
    </header>
    <main class="container">
        <section class="card">
            <h1>Créer un compte</h1>

            @if($errors->any())
                <div class="message-erreur">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input id="username" name="username" type="text" class="input" placeholder="Choisissez un nom d'utilisateur">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input id="password" name="password" type="password" class="input" placeholder="Choisissez un mot de passe">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="input" placeholder="Confirmez votre mot de passe">
                </div>
                <div class="form-actions">
                    <button class="btn" type="submit">Créer mon compte</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>