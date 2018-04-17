function json(response){
    return response.json();
}

function sendRating(action, rating, id){
    let url = `?action=${action}&rating=${rating}&id=${id}`;
    fetch(url, {
        method: 'post',
        headers: {
        "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body: `rating=${rating}&id=${id}`,
        credentials: 'include'
    })
    .then(json)
    .then((data) => {
        console.log('Request succeeded with JSON response', data);
        localStorage.setItem('hasVoted', 'yes');
        localStorage.setItem('id', id);
    })
    .catch((error) => {
        console.log('Request failed', error);
    });
}

window.addEventListener('load', () => {
    let like = document.querySelector('#btn-like');
    let dislike = document.querySelector('#btn-dislike');
    let articleId = document.querySelector('#article-id').value;

    like.addEventListener('click', () => {
        sendRating('rateArticle', like.value, articleId);
    });

    dislike.addEventListener('click', () => {
        sendRating('rateArticle', dislike.value, articleId);
    });
});