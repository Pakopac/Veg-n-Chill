function json(response){
    return response.json();
} 

window.addEventListener('load', () => {
    let sendMail = document.forms.contact;
    
    sendMail.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let mail = sendMail.elements.email;
        let mailObj = sendMail.elements.mailObj;
        let message = sendMail.elements.message;
        let url = "?action=contactUs";
        fetch(url, {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `mail=${mail.value}&object=${mailObj.value}&message=${message.value}`,
            credentials: 'include'
        })
        .then(json)
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
            if(data.success == "ok"){
                sendMail.reset();
                alert('Mail sent !');
            }
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });
});