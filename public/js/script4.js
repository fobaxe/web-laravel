// Valide les champs du formulaire de connexion.
function validateForm(){
    let nb_errors=0;

    // Verification du username.
    const username= document.querySelector('#username');
    const username_error= document.querySelector('#username-error');

    if(username.value == ""){
        username_error.classList.remove('titanic');
        nb_errors++;
    }
    else{
        username_error.classList.add('titanic');
    }

    // Verification du mot de passe.
    const password= document.querySelector('#password');
    const password_error= document.querySelector('#password-error');

    if(password.value == ""){
        password_error.classList.remove('titanic');
        nb_errors++;
    }
    else{
        password_error.classList.add('titanic');
    }
    return nb_errors;
}


// Formulaire de login principal.
const form = document.querySelector('#login-form');
form.addEventListener("submit",function(event){
    // Stoppe le submit natif, puis valide les champs.
    event.preventDefault();
    let error=validateForm();

    // Aucun message d'erreur -> soumission reelle du formulaire.
    if(error==0)
    {
       
        form.submit();
    }
});

