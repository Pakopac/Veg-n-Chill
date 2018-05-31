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
        .then(getComments())
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
}
function getComments() {
    let url = '?action=getComments';
    fetch(url, {
        method: 'get',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        credentials: 'include'
    })
        .then(json)
        .then((data) => {
            let blockComments = document.querySelectorAll('.blockComments')[0];
            console.log(blockComments);
            blockComments.innerHTML = '';
            for (let i in data) {
                    let divComment = document.createElement('div');
                    divComment.innerHTML = ` <img class="separateComments" src="./assets/images/separateNewsletter.svg">
            <div class="blockCommentImg">
                <img src="./assets/images/logo_sloth.svg" width="50px">
                <div class="comment">
                    <div class="commentPseudo">
                        ${data[i][4]}
                    </div>
                    <div class="commentContent">
                        ${data[i][2]}
                    </div>
                </div>
                <div class="dateComment">${data[i][5]}  <img src="./assets/images/dateCommentImg.svg" width="50px"></div>
            </div>`;
            blockComments.appendChild(divComment);
            }

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
window.addEventListener('load', () =>{
   getComments()
});