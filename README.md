# TicketFlow

Application web Laravel pour piloter des projets, suivre des tickets et tracer le temps passe par ticket.

Ce README est volontairement plus detaille pour expliquer le code et aider a reprendre le projet rapidement.

## Sommaire

1. Vision du projet
2. Fonctionnalites
3. Architecture du code
4. Flux applicatifs expliques
5. Schema de donnees
6. Controllers: role de chaque methode
7. Scripts JavaScript: quoi, ou, comment
8. Installation et lancement
9. Commandes utiles
10. Points d attention techniques
11. Idees d evolution

## 1) Vision du projet

TicketFlow permet a un utilisateur connecte de:

- creer et suivre ses projets
- creer, modifier et supprimer ses tickets
- changer les statuts de tickets et projets
- enregistrer du temps passe sur chaque ticket
- consulter des stats de profil (tickets ouverts/fermes, projets actifs, temps total)

Le projet est organise en MVC Laravel classique:

- routes dans `routes/web.php`
- logique metier dans `app/Http/Controllers`
- acces donnees via Eloquent dans `app/Models`
- rendu UI via Blade dans `resources/views`
- interactions front via scripts natifs dans `public/js`

## 2) Fonctionnalites

### Authentification

- login (`GET /`, `POST /`)
- inscription (`GET /register`, `POST /register`)
- logout (`POST /logout`)

### Projets

- liste, creation, edition, suppression
- mise a jour du statut (`PATCH /projets/{id}/statut`)
- vue detail projet avec tickets associes

### Tickets

- liste, creation, edition, suppression
- creation depuis un projet (`/projets/{id}/tickets/create`)
- mise a jour du statut (`PATCH /tickets/{id}/statut`)
- creation rapide via modale en `fetch` JSON (`POST /tickets/quick-store`)

### Suivi du temps

- ajout d entree de temps sur un ticket
- suppression d entree de temps

### Dashboard et profil

- dashboard avec tickets de l utilisateur
- page profil avec agregations (tickets, projets actifs, temps total)

## 3) Architecture du code

### Couche HTTP

Les routes sont definies dans `routes/web.php`.

- routes publiques pour auth
- groupe `Route::middleware('auth')` pour toutes les pages metier
- les requetes sont orientees vers les controllers `AuthController`, `TicketController`, `ProjetController`, `TempsPasseController`

### Couche metier (controllers)

Les controllers font 4 choses principales:

1. validation des entrees utilisateur
2. verification du perimetre utilisateur (`where('user_id', Auth::id())`)
3. operations CRUD sur modeles
4. redirection/retour JSON selon le contexte

### Couche donnees (Eloquent)

Modeles:

- `User`
- `Projet`
- `Ticket`
- `TempsPasse`

Relations:

- `Projet hasMany Ticket`
- `Ticket belongsTo Projet`
- `Ticket hasMany TempsPasse`
- `TempsPasse belongsTo Ticket`
- `Ticket` et `Projet` appartiennent a `User`

### Couche front

- vues Blade dans `resources/views`
- scripts front dedies dans `public/js`
- build assets via Vite (`resources/js/app.js`, `resources/js/bootstrap.js`, `vite.config.js`)

## 4) Flux applicatifs expliques

### Flux A - Connexion utilisateur

1. formulaire login envoie `POST /`
2. `AuthController@login` valide `username` et `password`
3. `Auth::attempt` authentifie
4. session regeneree puis redirection vers `dashboard`

En cas echec: retour sur la page avec message d erreur.

### Flux B - Creation ticket classique (formulaire)

1. utilisateur ouvre la page create ticket
2. front valide les champs obligatoires via `public/js/script1.js`
3. backend `TicketController@store` revalide serveur
4. insertion du ticket avec `user_id` et `projet_id`
5. redirection vers detail projet ou liste tickets

### Flux C - Creation ticket rapide (modale)

1. ouverture de modale depuis layout principal
2. validation locale dans `public/js/script-modal.js`
3. soumission `fetch` en JSON vers `POST /tickets/quick-store`
4. `TicketController@apiStore` valide et cree ticket
5. reponse JSON success puis refresh UI

Point important: la route de modale est une route web (pas `api.php`) pour conserver la session auth.

### Flux D - Suivi du temps

1. ajout de temps via `POST /tickets/{ticketId}/temps`
2. `TempsPasseController@store` valide puis verifie proprietaire ticket
3. insertion dans `temps_passes`
4. redirection vers detail ticket

## 5) Schema de donnees

Tables metier principales:

- `projets`
- `tickets`
- `temps_passes`
- `users`

### Table `projets`

Champs principaux:

- `id`
- `nom`
- `description` (nullable)
- `client`
- `priorite` enum: `basse`, `moyenne`, `haute`
- `statut` enum: `en-cours`, `planifié`, `terminé`
- `due`
- `user_id`

### Table `tickets`

Champs principaux:

- `id`
- `sujet`
- `description` (nullable)
- `client`
- `priorite` enum: `basse`, `moyenne`, `haute`
- `statut` enum: `ouvert`, `en cours`, `fermé`
- `due`
- `user_id`
- `projet_id`

### Table `temps_passes`

Champs principaux:

- `id`
- `ticket_id`
- `user_id`
- `date`
- `duree` (minutes)
- `commentaire` (nullable)

## 6) Controllers: role de chaque methode

### `AuthController`

- `showLogin`: affiche la vue login
- `login`: valide + authentifie
- `showRegister`: affiche la vue register
- `register`: valide + cree user (password hash)
- `logout`: deconnecte + invalide session

### `TicketController`

- `dashboard`: tickets user pour page dashboard
- `index`: liste tickets user
- `create`: formulaire creation ticket
- `createForProjet`: creation ticket contextualisee projet
- `store`: creation ticket via formulaire HTML
- `apiStore`: creation ticket via JSON `fetch`
- `show`: detail ticket + temps passes
- `edit` / `update`: edition ticket
- `destroy`: suppression ticket
- `updateStatut`: patch statut ticket

### `ProjetController`

- `index`: liste projets + count tickets
- `create` / `store`: creation projet
- `show`: detail projet + tickets + temps
- `edit` / `update`: edition projet
- `destroy`: suppression projet
- `updateStatut`: patch statut projet

### `TempsPasseController`

- `store`: ajoute une entree de temps sur ticket user
- `destroy`: supprime une entree de temps

## 7) Scripts JavaScript: quoi, ou, comment

### `public/js/script-modal.js`

Script de la modale globale:

- open/close (boutons, clic overlay, touche Escape)
- validation des champs requis
- creation payload JSON
- envoi `fetch` vers `/tickets/quick-store`
- affichage toast de succes puis reload

### `public/js/script1.js`

Script de validation et soumission du formulaire de creation ticket.

### `public/js/script2.js`

Filtres front sur tableau tickets:

- filtre par statut
- filtre par priorite

Le filtrage se base sur les attributs `data-statut` et `data-priorite` des lignes HTML.

### `public/js/script3.js`

Validation formulaire creation projet puis soumission du formulaire.

### `public/js/script4.js`

Validation formulaire login (username/password non vides).

## 8) Installation et lancement

### Prerequis

- PHP 8.3+
- Composer
- Node.js + npm
- SQLite (ou autre SGBD configure dans `.env`)

### Installation rapide (recommandee)

```bash
composer run setup
```

Le script fait:

1. `composer install`
2. creation `.env` si absent
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### Lancer en developpement

```bash
composer run dev
```

Lance en parallele:

- serveur Laravel
- queue listener
- pail logs
- Vite dev server

### Installation manuelle

```bash
composer install
copy .env.example .env
php artisan key:generate
type nul > database\\database.sqlite
php artisan migrate
npm install
npm run dev
```

## 9) Commandes utiles

```bash
# tests
composer run test

# vider config/cache route/view
php artisan optimize:clear

# relancer migrations a zero
php artisan migrate:fresh
```


## Licence

Projet base sur Laravel. Laravel est distribue sous licence MIT.
