// variable
let cards = document.querySelectorAll("article.card");
let stars = document.querySelectorAll(".fa-star");
let scores = document.querySelectorAll(".card__score--value");

// fct qui ajoute +1 au score de la carte et en gold l'étoile
for (let key in cards) {
    if (cards.hasOwnProperty.call(cards, key)) {
        let star = stars[key];
        let score = scores[key];
        star.addEventListener(`click`, () => {
            if (!star.classList.contains(`bg-dbz`)) {
                score.innerText++;
                star.classList.add(`bg-dbz`);
            } else {
                score.innerText--;
                star.classList.remove(`bg-dbz`);
            }
        });
    }
}