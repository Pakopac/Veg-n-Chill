function json(response){
    return response.json();
}

function sendComment(action, content, idNews){
    let url = `?action=${action}&contentComment=${content}&idNews=${idNews}`;
    fetch(url, {
        method: 'post',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body: `contentComment=${content}&idNews=${idNews}`,
        credentials: 'include'
    })
        .then(json)
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
}
let btnComment = document.querySelectorAll('.buttonComments')[1];
btnComment.addEventListener('click', () => {
    sendComment('addComment', document.querySelector('#inputComment').value,'1');
    document.querySelector('#inputComment').value = '';
});