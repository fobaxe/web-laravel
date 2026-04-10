<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/log.css') }}">
    <title>Se connecter</title>
</head>
<body>
    <header>
        <a href="{{ route('login') }}">
            <img class="logo" src="{{ asset('assets/logo.png') }}" alt="Logo du site">
        </a>
    </header>
    <main class="container">
        <section class="card">
            <h1 id="login-title">Se connecter</h1>

            @if($errors->any())
                <div class="message-erreur">{{ $errors->first() }}</div>
            @endif

            @if(session('success'))
                <div class="message-erreur" style="color:green">{{ session('success') }}</div>
            @endif

            <form id="login-form" action="{{ route('login') }}" method="POST" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input id="username" name="username" type="text" class="input" placeholder="Votre nom d'utilisateur">
                    <div id="username-error" class="message-erreur titanic">Le nom d'utilisateur est obligatoire</div>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input id="password" name="password" type="password" class="input" placeholder="Mot de passe">
                    <div id="password-error" class="message-erreur titanic">Le mot de passe est obligatoire</div>
                </div>
                <div class="form-help">
                    <a class="btn-compte" href="{{ route('register') }}">Créer un compte</a>
                    <a href="{{ route('mdp') }}" class="forgot-link">Mot de passe oublié ?</a>
                </div>
                <div class="form-actions">
                    <button class="btn" type="submit">Connexion</button>
                </div>
            </form>
        </section>
    </main>
    <script src="{{ asset('js/script4.js') }}"></script>
</body>
</html>