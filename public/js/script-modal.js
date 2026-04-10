// ── Éléments DOM ──────────────────────────────────────────────
const overlay   = document.getElementById('modal-overlay');
const btnOpen   = document.getElementById('btn-open-modal');
const btnClose  = document.getElementById('btn-close-modal');
const btnCancel = document.getElementById('btn-cancel-modal');
const form      = document.getElementById('modal-ticket-form');
const toast     = document.getElementById('modal-toast');

// ── Ouvrir / fermer la modale ─────────────────────────────────
function openModal() {
    overlay.classList.add('open');
    document.getElementById('m-subject').focus();
}

function closeModal() {
    overlay.classList.remove('open');
    resetForm();
}

btnOpen.addEventListener('click', openModal);
btnClose.addEventListener('click', closeModal);
btnCancel.addEventListener('click', closeModal);

overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
});

// ── Validation ────────────────────────────────────────────────
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

function validateForm() {
    const ok1 = validateField('m-subject', 'm-subject-error');
    const ok2 = validateField('m-client',  'm-client-error');
    const ok3 = validateField('m-due',     'm-due-error');
    const ok4 = validateField('m-projet',  'm-projet-error');
    return ok1 && ok2 && ok3 && ok4;
}

function resetForm() {
    form.reset();
    document.querySelectorAll('.field-error').forEach(el => el.classList.remove('visible'));
    toast.classList.remove('visible');
}

// ── Soumission via fetch() → POST /tickets/quick-store ────────
// On pointe sur une route WEB (pas API) pour que la session
// Laravel soit reconnue et que Auth::id() fonctionne
form.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!validateForm()) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const payload = {
        subject:     document.getElementById('m-subject').value.trim(),
        client:      document.getElementById('m-client').value.trim(),
        description: document.getElementById('m-description').value.trim(),
        due:         document.getElementById('m-due').value,
        priority:    document.getElementById('m-priority').value,
        status:      document.getElementById('m-status').value,
        projet_id:   document.getElementById('m-projet').value,
    };

    try {
        const response = await fetch('/tickets/quick-store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            toast.classList.add('visible');
            setTimeout(() => {
                closeModal();
                window.location.reload();
            }, 1500);
        } else {
            const messages = data.errors
                ? Object.values(data.errors).flat().join('\n')
                : (data.message ?? 'Une erreur est survenue.');
            alert(messages);
        }

    } catch (err) {
        console.error('Erreur réseau :', err);
        alert('Impossible de contacter le serveur.');
    }
});
