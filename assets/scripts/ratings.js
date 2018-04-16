function json(response){
    return response.json();
}

function sendRating(rating, id){
    let url = `?action=rateArticle&rating=${rating}&id=${id}`;
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
    let oneStar = document.querySelector('#btn-one-star');
    let twoStars = document.querySelector('#btn-two-stars');
    let threeStars = document.querySelector('#btn-three-stars');
    let fourStars = document.querySelector('#btn-four-stars');
    let fiveStars = document.querySelector('#btn-five-stars');
    let articleId = document.querySelector('#article-id').value;

    oneStar.addEventListener('click', () => {
        sendRating(oneStar.value, articleId);
    });

    twoStars.addEventListener('click', () => {
        sendRating(twoStars.value, articleId);
    });

    threeStars.addEventListener('click', () => {
        sendRating(threeStars.value, articleId);
    });

    fourStars.addEventListener('click', () => {
        sendRating(fourStars.value, articleId);
    });

    fiveStars.addEventListener('click', () => {
        sendRating(fiveStars.value, articleId);
    });
});