// Filtre par statut
const filtres = document.querySelectorAll(".btn-filtre");

for (let i = 0; i < filtres.length; i++) {
    filtres[i].addEventListener("click", function(event) {
        event.preventDefault();

        const filtre = filtres[i].innerText.trim().toLowerCase();
        const rows = document.querySelectorAll('.card tbody tr');

        for (let j = 0; j < rows.length; j++) {
            if (filtre === "tous") {
                rows[j].classList.remove('titanic');
                continue;
            }
            // On compare avec data-statut stocké en base (ex: "en cours", "ouvert", "fermé")
            if (rows[j].dataset.statut.toLowerCase() !== filtre) {
                rows[j].classList.add('titanic');
            } else {
                rows[j].classList.remove('titanic');
            }
        }
    });
}

// Filtre par priorité
const filtres1 = document.querySelectorAll(".btn-filtre1");

for (let i = 0; i < filtres1.length; i++) {
    filtres1[i].addEventListener("click", function(event) {
        event.preventDefault();

        const filtre = filtres1[i].innerText.trim().toLowerCase();
        const rows = document.querySelectorAll('.card tbody tr');

        for (let j = 0; j < rows.length; j++) {
            if (filtre === "toutes") {
                rows[j].classList.remove('titanic');
                continue;
            }
            // On compare avec data-priorite stocké en base (ex: "haute", "moyenne", "basse")
            if (rows[j].dataset.priorite.toLowerCase() !== filtre) {
                rows[j].classList.add('titanic');
            } else {
                rows[j].classList.remove('titanic');
            }
        }
    });
}
