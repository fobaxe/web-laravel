// Validation des champs obligatoires
function check_form() {
    let nb_errors = 0;

    const client = document.querySelector('#client');
    const client_error = document.querySelector('#client-error');
    if (client.value === '') {
        client_error.classList.remove('titanic');
        nb_errors++;
    } else {
        client_error.classList.add('titanic');
    }

    const subject = document.querySelector('#subject');
    const subject_error = document.querySelector('#subject-error');
    if (subject.value === '') {
        subject_error.classList.remove('titanic');
        nb_errors++;
    } else {
        subject_error.classList.add('titanic');
    }

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

const form = document.querySelector('#create-ticket-form');

form.addEventListener('submit', async function (event) {
    // On empêche toujours le rechargement de page
    event.preventDefault();

    const errors = check_form();
    if (errors > 0) return;

    // Récupération des valeurs du formulaire
    const subject  = document.querySelector('#subject').value;
    const client   = document.querySelector('#client').value;
    const due      = document.querySelector('#due').value;
    const priority = document.querySelector('#priority').value;
    const status   = document.querySelector('#status').value;

    // Récupération du token CSRF depuis la balise meta de Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        // Appel API avec fetch() vers POST /api/tickets
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
            // Affichage du toast de succès
            const toast = document.querySelector('#success');
            toast.classList.remove('titanic');
            setTimeout(() => toast.classList.add('titanic'), 2000);

            // Réinitialisation du formulaire
            document.querySelector('#subject').value = '';
            document.querySelector('#client').value  = '';
            document.querySelector('#due').value      = '';
        } else {
            // Affichage des erreurs de validation retournées par Laravel
            console.error('Erreurs API :', data.errors ?? data.message);
            alert('Erreur : ' + (data.message ?? 'Impossible de créer le ticket.'));
        }
    } catch (err) {
        console.error('Erreur réseau :', err);
        alert('Une erreur réseau est survenue.');
    }
});
