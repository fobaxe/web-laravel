function check_form(){
    let nb_errors = 0;
    const Client = document.querySelector('#client');
    const client_error = document.querySelector('#client-error');
    if (Client.value == ""){
        client_error.classList.remove('titanic');
        nb_errors++;
    }
    else{
        client_error.classList.add('titanic');
    }
    const nom = document.querySelector('#nom');
    const nom_error = document.querySelector('#nom-error');
    if (nom.value == "") {
        nom_error.classList.remove('titanic');
        nb_errors++;
    } else {
        nom_error.classList.add('titanic');
    }
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

const form= document.querySelector('#create-project-form');
form.addEventListener("submit", function(event){
    event.preventDefault();
    console.log("submit form");
    let errors = check_form();
    console.log("nb_errors : ", errors);
    if (errors == 0){
        const nom = document.querySelector('#nom');
        const client = document.querySelector('#client');
        const due = document.querySelector('#due');
        console.log(nom.value, client.value, due.value);
        form.submit();
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

