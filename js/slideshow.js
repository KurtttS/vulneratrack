

function moveSlide(direction) {
    const slider = document.getElementById('slider');
    const slides = document.querySelectorAll('.user-card');
    const totalSlides = slides.length;
    
    currentIndex += direction;

    if (currentIndex >= totalSlides) {
        currentIndex = 0;
    } else if (currentIndex < 0) {
        currentIndex = totalSlides - 1;
    }

    const offset = -currentIndex * 100;
    slider.style.transform = `translateX(${offset}%)`;
}
const counters = document.querySelectorAll('.counter');

const startCount = (el) => {
    const target = +el.getAttribute('data-target');
    const duration = 4000; 
    const start = 0;
    const startTime = performance.now();

    const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const easeOut = 1 - Math.pow(1 - progress, 3);
        
        el.innerText = Math.floor(easeOut * target);

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            el.innerText = target;
        }
    };

    requestAnimationFrame(update);
};

const observerOptions = {
    threshold: 0.5 
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            startCount(entry.target);
            observer.unobserve(entry.target); 
        }
    });
}, observerOptions);

counters.forEach(counter => observer.observe(counter));


//ANIMATION HOME

document.addEventListener("DOMContentLoaded", () => {
    const animate = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');

            }
        });
    }, {
        threshold: 0.1 
    });

    const hiddenElements = document.querySelectorAll('.mainbox, .box, .card, .hotline-board, .contact-card');
    
    hiddenElements.forEach((el) => animate.observe(el));
});




let currentIndex = 0;
let autoSlide;
let slides;
let dots;

document.addEventListener("DOMContentLoaded", () => {

    slides = document.querySelectorAll('.user-card');

    const dotsContainer = document.getElementById("sliderDots");

    // build dots
    slides.forEach((_, i) => {
        const dot = document.createElement("span");
        dot.classList.add("dot");
        dot.addEventListener("click", () => goToSlide(i));
        dotsContainer.appendChild(dot);
    });

    dots = document.querySelectorAll(".dot");

    updateSlider();

    autoSlide = setInterval(() => moveSlide(1), 3000);
});


function moveSlide(direction) {

    clearInterval(autoSlide);

    const totalSlides = slides.length;

    currentIndex += direction;

    if (currentIndex >= totalSlides) currentIndex = 0;
    if (currentIndex < 0) currentIndex = totalSlides - 1;

    updateSlider();

    autoSlide = setInterval(() => moveSlide(1), 5000);
}


function goToSlide(index){

    clearInterval(autoSlide);

    currentIndex = index;
    updateSlider();

    autoSlide = setInterval(() => moveSlide(1), 5000);
}


function updateSlider(){

    const slider = document.getElementById('slider');

    const offset = -currentIndex * 100;
    slider.style.transform = `translateX(${offset}%)`;

    slides.forEach(s => s.classList.remove("active"));
    slides[currentIndex].classList.add("active");

    dots.forEach(d => d.classList.remove("active"));
    dots[currentIndex].classList.add("active");
}