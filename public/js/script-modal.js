// Recupere tous les elements de la modale utilises dans le script.
const overlay = document.getElementById('modal-overlay');
const btnOpen = document.getElementById('btn-open-modal');
const btnClose = document.getElementById('btn-close-modal');
const btnCancel = document.getElementById('btn-cancel-modal');
const form = document.getElementById('modal-ticket-form');
const toast = document.getElementById('modal-toast');

// Ouvre la modale et place le curseur dans le premier champ utile.
function openModal() {
    overlay.classList.add('open');
    document.getElementById('m-subject').focus();
}

// Ferme la modale puis remet le formulaire a zero.
function closeModal() {
    overlay.classList.remove('open');
    resetForm();
}

// Branche les boutons d'ouverture/fermeture.
btnOpen.addEventListener('click', openModal);
btnClose.addEventListener('click', closeModal);
btnCancel.addEventListener('click', closeModal);

// Si on clique en dehors de la fenetre, on ferme la modale.
overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
});

// Touche Echap = fermeture rapide quand la modale est ouverte.
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
});

// Verifie qu'un champ n'est pas vide et affiche/cache son message d'erreur.
function validateField(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    if (input.value.trim() === '') {
        error.classList.add('visible');
        return false;
    }
    error.classList.remove('visible');
    return true;
}

// Valide les champs obligatoires de la modale.
function validateForm() {
    const ok1 = validateField('m-subject', 'm-subject-error');
    const ok2 = validateField('m-client', 'm-client-error');
    const ok3 = validateField('m-due', 'm-due-error');
    const ok4 = validateField('m-projet', 'm-projet-error');
    return ok1 && ok2 && ok3 && ok4;
}

// Nettoie le formulaire et retire les messages visuels.
function resetForm() {
    form.reset();
    document.querySelectorAll('.field-error').forEach(el => el.classList.remove('visible'));
    toast.classList.remove('visible');
}

// Soumet le formulaire en fetch vers la route api Laravel.
form.addEventListener('submit', async function (e) {
    e.preventDefault();

    // Stoppe l'envoi si la validation locale echoue.
    if (!validateForm()) return;

    // Recupere le token CSRF ajoute par Laravel dans la balise <meta>.
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Construit la charge utile envoyee au serveur.
    const payload = {
        subject: document.getElementById('m-subject').value.trim(),
        client: document.getElementById('m-client').value.trim(),
        description: document.getElementById('m-description').value.trim(),
        due: document.getElementById('m-due').value,
        priority: document.getElementById('m-priority').value,
        status: document.getElementById('m-status').value,
        projet_id: document.getElementById('m-projet').value,
    };

    try {
        // Envoi HTTP en JSON avec les en-tetes attendus par Laravel.
        const response = await fetch('/tickets/quick-store', {
            method: 'POST',
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
            // Affiche une confirmation, puis recharge la page pour voir le nouveau ticket.
            toast.classList.add('visible');
            setTimeout(() => {
                closeModal();
                window.location.reload();
            }, 1500);
        } else {
            // Concatene les erreurs de validation Laravel pour les afficher a l'utilisateur.
            const messages = data.errors
                ? Object.values(data.errors).flat().join('\n')
                : (data.message ?? 'Une erreur est survenue.');
            alert(messages);
        }

    } catch (err) {
        // Gestion des erreurs reseau (serveur indisponible, coupure, etc.).
        console.error('Erreur réseau :', err);
        alert('Impossible de contacter le serveur.');
    }
});
