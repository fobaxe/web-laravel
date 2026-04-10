// Liste des boutons qui filtrent les tickets par statut.
const filtres = document.querySelectorAll(".btn-filtre");

for (let i = 0; i < filtres.length; i++) {
    filtres[i].addEventListener("click", function(event) {
        // On garde l'utilisateur sur la page et on filtre en JavaScript.
        event.preventDefault();

        // Le texte du bouton devient la valeur de filtre (ex: "ouvert").
        const filtre = filtres[i].innerText.trim().toLowerCase();
        const rows = document.querySelectorAll('.card tbody tr');

        for (let j = 0; j < rows.length; j++) {
            // "Tous" affiche toutes les lignes sans condition.
            if (filtre === "tous") {
                rows[j].classList.remove('titanic');
                continue;
            }
            // Compare le filtre avec data-statut de la ligne du ticket.
            if (rows[j].dataset.statut.toLowerCase() !== filtre) {
                rows[j].classList.add('titanic');
            } else {
                rows[j].classList.remove('titanic');
            }
        }
    });
}

// Liste des boutons qui filtrent les tickets par priorite.
const filtres1 = document.querySelectorAll(".btn-filtre1");

for (let i = 0; i < filtres1.length; i++) {
    filtres1[i].addEventListener("click", function(event) {
        // Meme logique: filtre local sans rechargement de page.
        event.preventDefault();

        // Le texte du bouton (haute/moyenne/basse/toutes) est la cle de tri.
        const filtre = filtres1[i].innerText.trim().toLowerCase();
        const rows = document.querySelectorAll('.card tbody tr');

        for (let j = 0; j < rows.length; j++) {
            // "Toutes" retire le filtre de priorite.
            if (filtre === "toutes") {
                rows[j].classList.remove('titanic');
                continue;
            }
            // Compare le filtre avec data-priorite de la ligne du ticket.
            if (rows[j].dataset.priorite.toLowerCase() !== filtre) {
                rows[j].classList.add('titanic');
            } else {
                rows[j].classList.remove('titanic');
            }
        }
    });
}
