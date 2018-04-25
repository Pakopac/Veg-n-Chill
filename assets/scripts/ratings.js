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

let like = document.querySelector('#btn-like');
let dislike = document.querySelector('#btn-dislike');
let articleId = document.querySelector('#article-id').value;
let voteArticle = document.querySelector('#voteArticle');

let likeComment = document.querySelectorAll('.btn-like-comment');
let dislikeComment = document.querySelectorAll('.btn-dislike-comment');
let commentId = document.querySelectorAll('.comment-id');
let voteComment = document.querySelectorAll('.voteComment');

window.addEventListener('load', () => {


    like.addEventListener('click', () => {
        sendRating('rateArticle', like.value, articleId);
        localStorage.setItem('vote article', 'like');
        like.style.display = 'none';
        dislike.style.display = 'none';
        voteArticle.innerHTML = 'voted like'
    });

    dislike.addEventListener('click', () => {
        sendRating('rateArticle', dislike.value, articleId);
        localStorage.setItem('vote article', 'dislike');
        like.style.display = 'none';
        dislike.style.display = 'none';
        voteArticle.innerHTML = 'voted dislike'
    });
    fctLikeComment(dislikeComment, 'dislike');
    fctLikeComment(likeComment, 'like');

    verifyVote();

});
function fctLikeComment(action, rating) {
    for (let i = 0; i < action.length; i++){
        action[i].addEventListener('click', () => {
            sendRating('rateComment', rating, commentId[i].value);
            localStorage.setItem('vote ' + commentId[i].value, rating);
            likeComment[i].style.display = 'none';
            dislikeComment[i].style.display = 'none';
            voteComment[i].innerHTML = 'voted ' + rating
        })
    }
}

function verifyVote(){
    for (let i=0; i < commentId.length; i++) {
        if (typeof localStorage.getItem('vote ' + commentId[i].value) !== 'undefined'
            && localStorage.getItem('vote ' + commentId[i].value) === 'like' ||
            typeof localStorage.getItem('vote ' + commentId[i].value) !== 'undefined'
            && localStorage.getItem('vote ' + commentId[i].value) === 'dislike') {

            likeComment[i].style.display = 'none';
            dislikeComment[i].style.display = 'none';
            if (localStorage.getItem('vote ' + commentId[i].value) === 'like'){
                voteComment[i].innerHTML = 'voted like'
            }
            if (localStorage.getItem('vote ' + commentId[i].value) === 'dislike'){
                voteComment[i].innerHTML = 'voted dislike'
            }
        }
    }
    console.log(localStorage.getItem('vote article'));
    if(typeof localStorage.getItem('vote article') !== 'undefined' &&
        localStorage.getItem('vote article') === 'like' ||
        localStorage.getItem('vote article') !== 'undefined' &&
        localStorage.getItem('vote article') === 'dislike'){

        like.style.display = 'none';
        dislike.style.display = 'none';
        if (localStorage.getItem('vote article') === 'like'){
            voteArticle.innerHTML = 'voted like';
        }
        if (localStorage.getItem('vote article') === 'dislike'){
            voteArticle.innerHTML = 'voted dislike';
        }
    }
}
