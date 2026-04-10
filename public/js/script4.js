function validateForm(){
    let nb_errors=0;

    const username= document.querySelector('#username');
    const username_error= document.querySelector('#username-error');

    if(username.value == ""){
        username_error.classList.remove('titanic');
        nb_errors++;
    }
    else{
        username_error.classList.add('titanic');
    }

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


const form = document.querySelector('#login-form');
form.addEventListener("submit",function(event){
    event.preventDefault();
    let error=validateForm();
    if(error==0)
    {
       
        form.submit();
    }
});

