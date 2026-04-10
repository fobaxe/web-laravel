<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/log.css') }}">
    <title>Réinitialiser mot de passe</title>
</head>
<body>
    <header>
        <a href="{{ route('login') }}">
            <img class="logo" src="{{ asset('assets/logo.png') }}" alt="Logo du site">
        </a>
    </header>
    <main class="container">
        <section class="card">
            <h1 id="login-title">Réinitialiser mot de passe</h1>
            <form action="" method="POST" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input id="email" name="email" type="email" class="input" placeholder="Votre adresse e-mail" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn">Envoyer le lien de réinitialisation</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>