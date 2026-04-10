// Verifie les champs obligatoires du formulaire de creation de projet.
function check_form(){
    let nb_errors = 0;

    // Le client est obligatoire.
    const Client = document.querySelector('#client');
    const client_error = document.querySelector('#client-error');
    if (Client.value == ""){
        client_error.classList.remove('titanic');
        nb_errors++;
    }
    else{
        client_error.classList.add('titanic');
    }

    // Le nom du projet est obligatoire.
    const nom = document.querySelector('#nom');
    const nom_error = document.querySelector('#nom-error');
    if (nom.value == "") {
        nom_error.classList.remove('titanic');
        nb_errors++;
    } else {
        nom_error.classList.add('titanic');
    }

    // La date d'echeance est obligatoire.
    const due = document.querySelector('#due');
    const due_error = document.querySelector('#due-error');
    if (due.value == ""){
        due_error.classList.remove('titanic');
        nb_errors++;
    }
    else{
        due_error.classList.add('titanic');
    }
    return nb_errors;
}

// Formulaire cible pour creer un projet.
const form= document.querySelector('#create-project-form');
form.addEventListener("submit", function(event){
    // On bloque le submit HTML pour valider d'abord en front.
    event.preventDefault();
    console.log("submit form");
    let errors = check_form();
    console.log("nb_errors : ", errors);

    // Si tout est valide, on laisse le navigateur soumettre le formulaire.
    if (errors == 0){
        const nom = document.querySelector('#nom');
        const client = document.querySelector('#client');
        const due = document.querySelector('#due');
        console.log(nom.value, client.value, due.value);

        // Envoi vers le backend Laravel.
        form.submit();

        // Nettoie localement les champs et affiche un toast court.
        nom.value = "";
        client.value = "";
        due.value = ""; 
        const toast = document.querySelector("#success");
        console.log(toast);
        toast.classList.remove('titanic'); 
        setTimeout(() => {
            toast.classList.add('titanic');
        }, 1000);
      
    }
})

