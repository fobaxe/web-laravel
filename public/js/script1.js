// Verifie les champs obligatoires avant l'envoi du ticket.
function check_form() {
    let nb_errors = 0;

    // Champ client obligatoire.
    const client = document.querySelector('#client');
    const client_error = document.querySelector('#client-error');
    if (client.value === '') {
        client_error.classList.remove('titanic');
        nb_errors++;
    } else {
        client_error.classList.add('titanic');
    }

    // Champ sujet obligatoire.
    const subject = document.querySelector('#subject');
    const subject_error = document.querySelector('#subject-error');
    if (subject.value === '') {
        subject_error.classList.remove('titanic');
        nb_errors++;
    } else {
        subject_error.classList.add('titanic');
    }

    // Date d'echeance obligatoire.
    const due = document.querySelector('#due');
    const due_error = document.querySelector('#due-error');
    if (due.value === '') {
        due_error.classList.remove('titanic');
        nb_errors++;
    } else {
        due_error.classList.add('titanic');
    }

    return nb_errors;
}

// Formulaire de creation de ticket.
const form = document.querySelector('#create-ticket-form');

form.addEventListener('submit', async function (event) {
    // Empeche le submit HTML classique pour passer par fetch().
    event.preventDefault();

    const errors = check_form();
    // Si au moins une erreur est presente, on stoppe ici.
    if (errors > 0) return;

    // Recupere les valeurs saisies.
    const subject  = document.querySelector('#subject').value;
    const client   = document.querySelector('#client').value;
    const due      = document.querySelector('#due').value;
    const priority = document.querySelector('#priority').value;
    const status   = document.querySelector('#status').value;

    // Token CSRF necessaire pour les requetes POST Laravel.
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        // Appel API vers la route de creation de ticket.
        const response = await fetch('/api/tickets', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ subject, client, due, priority, status }),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Affiche un toast si la creation est reussie.
            const toast = document.querySelector('#success');
            toast.classList.remove('titanic');
            setTimeout(() => toast.classList.add('titanic'), 2000);

            // Nettoie quelques champs pour un nouvel ajout.
            document.querySelector('#subject').value = '';
            document.querySelector('#client').value  = '';
            document.querySelector('#due').value      = '';
        } else {
            // Affiche les erreurs remontees par le backend Laravel.
            console.error('Erreurs API :', data.errors ?? data.message);
            alert('Erreur : ' + (data.message ?? 'Impossible de créer le ticket.'));
        }
    } catch (err) {
        // Gestion des problemes reseau/cote serveur.
        console.error('Erreur réseau :', err);
        alert('Une erreur réseau est survenue.');
    }
});
