let currentIndex = 0;

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

    const hiddenElements = document.querySelectorAll('.mainbox, .box, .card');
    
    hiddenElements.forEach((el) => animate.observe(el));
});