window.addEventListener('load', () => {
   let titleIngredient1 = document.querySelector('#titleIngredient1');
   let titleIngredient2 = document.querySelector('#titleIngredient2');
   let titleIngredient3 = document.querySelector('#titleIngredient3');
   let ingredient1 = document.querySelector('#ingredient1');
   let ingredient2 = document.querySelector('#ingredient2');
   let ingredient3 = document.querySelector('#ingredient3');
   let scroll1 = 'yes';
   let scroll2 = 'no';
   let scroll3 = 'no';
   let arrow1 = document.querySelector('#scroll1');
   let arrow2 = document.querySelector('#scroll2');
   let arrow3 = document.querySelector('#scroll3');
    let scrollTop = document.createElement('img');
    scrollTop.setAttribute('src', "./assets/images/scroll-top.svg");

    let scroll = (titleIngredient, ingredientBlock, ingredientNone1, ingredientNone2, scroll, arrow, otherArrow1, otherArrow2) => {
        titleIngredient.addEventListener('click', () => {
            if (scroll === 'no') {
                ingredientBlock.style.display = 'block';
                ingredientNone1.style.display = 'none';
                ingredientNone2.style.display = 'none';
                scroll = 'yes';
                arrow.setAttribute('src', './assets/images/scroll-bot.svg');x
                otherArrow1.setAttribute('src', './assets/images/scroll-top.svg');
                otherArrow2.setAttribute('src', './assets/images/scroll-top.svg');
            }
            else{
                arrow.setAttribute('src', './assets/images/scroll-top.svg');
                otherArrow1.setAttribute('src', './assets/images/scroll-top.svg');
                otherArrow2.setAttribute('src', './assets/images/scroll-top.svg');
                ingredientBlock.style.display = 'none';
                scroll = 'no'
            }
        })
    };

    scroll(titleIngredient1, ingredient1, ingredient2, ingredient3, scroll1, arrow1, arrow2, arrow3);
    scroll(titleIngredient2, ingredient2, ingredient1, ingredient3, scroll2, arrow2, arrow1, arrow3);
    scroll(titleIngredient3, ingredient3, ingredient1, ingredient2, scroll3, arrow3, arrow1, arrow2);
});