/******************Add the story ******************/
const image_profile = [
    ['https://www.springboard.com/blog/wp-content/uploads/2023/09/what-exactly-does-a-programmer-do.jpeg','AmirAli'],
    ['https://i.ibb.co/Zhc5hHp/account4.jpg','setayesh'],
    ['https://i.ibb.co/cx69NJL/account3-1.jpg','elina'],
    ['https://i.ibb.co/kD6tj9T/account2.jpg','maryam'],
    ['https://i.ibb.co/SPTNbJL/account5.jpg','amin'],
    ['https://i.ibb.co/nj8pPqZ/account6.jpg','No Name'],
    ['https://i.ibb.co/vkXPdxN/account7.jpg','No Name'],
    ['https://i.ibb.co/7R0Vzp3/account8.jpg','No Name'],
    ['https://i.ibb.co/gvrfhjL/account9.jpg','No Name'],
    ['https://i.ibb.co/j8L7FPY/account10.jpg','No Name'],
    ['https://i.ibb.co/JcXRPht/account11.jpg','No Name'],
    ['https://i.ibb.co/6WvdZS9/account12.jpg','No Name'],
    ['https://i.ibb.co/pJ8thst/account13.jpg','No Name'],
    ['https://i.ibb.co/4M3W996/account14.jpg','No Name'],
    ['https://i.ibb.co/Fzpg5yd/account15.jpg','No Name'],
]
const story_container = document.querySelector('.owl-carousel.items');
if(story_container){
    for (var i = 0; i < image_profile.length; i++) {
        const parentDiv = document.createElement('div');
        parentDiv.classList.add("item_s");
        parentDiv.innerHTML = `
            <img src="${image_profile[i][0]}">
            <p>${image_profile[i][1]}</p>
            `;
        story_container.appendChild(parentDiv);
    }
}


$(document).ready(function(){
    $(".owl-carousel").owlCarousel();
});

$('.owl-carousel').owlCarousel({
    loop:true,
    margin:5,
    responsiveClass:true,
    responsive:{
        0:{
            items:5,
            nav:true
        },
        500:{
            items:7,
            nav:false
        }
    }
})