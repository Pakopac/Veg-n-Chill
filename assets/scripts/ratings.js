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
    })
    .catch((error) => {
        console.log('Request failed', error);
    });
}

let dislikeComment = document.querySelectorAll('.btn-dislike-comment');
let likeComment = document.querySelectorAll('.btn-like-comment');

let like = document.querySelector('#btn-like');
let dislike = document.querySelector('#btn-dislike');
let articleId = document.querySelector('#article-id').value;
let commentId = document.querySelectorAll('.comment-id');

window.addEventListener('load', () => {


    like.addEventListener('click', () => {
        sendRating('rateArticle', like.value, articleId);
        localStorage.setItem('vote article', 'yes');
        like.style.display = 'none';
        dislike.style.display = 'none';
    });

    dislike.addEventListener('click', () => {
        sendRating('rateArticle', dislike.value, articleId);
        localStorage.setItem('vote article', 'yes');
        like.style.display = 'none';
        dislike.style.display = 'none';
    });
    fctLikeComment(dislikeComment, 'dislike');
    fctLikeComment(likeComment, 'like');

    verifyVote();

});
function fctLikeComment(action, rating) {
    for (let i = 0; i < action.length; i++){
        action[i].addEventListener('click', () => {
            sendRating('rateComment', rating, commentId[i].value);
            localStorage.setItem('vote ' + commentId[i].value, 'yes');
            likeComment[i].style.display = 'none';
            dislikeComment[i].style.display = 'none';
        })
    }
}

function verifyVote(){
    for (let i=0; i < commentId.length; i++) {
        console.log(commentId[i]);
        if (typeof localStorage.getItem('vote ' + commentId[i].value) !== 'undefined'
            && localStorage.getItem('vote ' + commentId[i].value) === 'yes') {
            console.log('ok');
            likeComment[i].style.display = 'none';
            dislikeComment[i].style.display = 'none';
        }
    }
    if(typeof localStorage.getItem('vote article') !== 'undefined'
        && localStorage.getItem('vote article') === 'yes'){
        like.style.display = 'none';
        dislike.style.display = 'none';
    }
}
