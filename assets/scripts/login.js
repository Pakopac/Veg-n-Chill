function json(response){
    return response.json();
} 

window.addEventListener('load', () => {

    /***********
    * Register *
    ***********/
    let register = document.forms.register;
    
    register.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let firstname = register.elements.firstname;
        let lastname = register.elements.lastname;
        let user = register.elements.pseudo;
        let pass = register.elements.password;
        let passwordRepeat = register.elements.repeatPassword;
        let email = register.elements.email;
        let url = '?action=register';
        fetch(url, {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `firstname=${firstname.value}&lastname=${lastname.value}&username=${user.value}&password=${pass.value}&password_repeat=${passwordRepeat.value}&email=${email.value}`,
            credentials: 'include'
        })
        .then(json)
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
            firstname.value = "";
            lastname.value = "";
            user.value = "";
            pass.value = "";
            passwordRepeat.value = "";
            email.value = "";
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });

    /********
    * LOGIN *
    ********/

    let login = document.forms.login;

    login.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let username = login.elements.pseudo;
        let password = login.elements.password;
        let url = '?action=login';
        fetch(url, {
            method: 'post',
            headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `username=${username.value}&password=${password.value}`,
            credentials: 'include'
        })
        .then(json)
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
            if(data.status === "ok"){
                let el = document.querySelector('.overlay');
                let btnAccount = document.querySelector('.btn');
                btnAccount.href = "#TODO-ACCOUNT";
                btnAccount.textContent = 'Mon Espace';
                el.style.opacity = 0;
            }
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });
});