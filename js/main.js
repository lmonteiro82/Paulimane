// Navbar scroll effect + shrink + transparent
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    const backToTop = document.getElementById('backToTop');
    if (!navbar) return;
    const scrolled = window.scrollY > 50;
    
    // Add scrolled class for transparent navbar
    navbar.classList.toggle('scrolled', scrolled);
    navbar.classList.toggle('shrink', scrolled);

    // Toggle back-to-top button visibility
    if (backToTop) {
        backToTop.classList.toggle('show', window.scrollY > 200);
    }
});

// Back to top click handler
const backToTopBtn = document.getElementById('backToTop');
if (backToTopBtn) {
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// Mobile menu toggle
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });

    // Close menu when clicking on a link
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });
}

// Hero Carousel
const heroCarousel = document.getElementById('heroCarousel');
if (heroCarousel) {
    const slides = heroCarousel.querySelectorAll('.carousel-slide');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const indicatorsContainer = document.getElementById('indicators');
    
    let currentSlide = 0;
    let autoplayInterval;

    // Create indicators
    slides.forEach((_, index) => {
        const indicator = document.createElement('div');
        indicator.classList.add('indicator');
        if (index === 0) indicator.classList.add('active');
        indicator.addEventListener('click', () => goToSlide(index));
        indicatorsContainer.appendChild(indicator);
    });

    const indicators = indicatorsContainer.querySelectorAll('.indicator');

    function updateSlides() {
        slides.forEach((slide, index) => {
            slide.classList.remove('active');
            indicators[index].classList.remove('active');
        });
        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');
        // Move track to show the current slide
        heroCarousel.style.transform = `translateX(-${currentSlide * 100}%)`;
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        updateSlides();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        updateSlides();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlides();
        resetAutoplay();
    }

    function startAutoplay() {
        autoplayInterval = setInterval(nextSlide, 5000);
    }

    function resetAutoplay() {
        clearInterval(autoplayInterval);
        startAutoplay();
    }

    if (prevBtn) prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoplay();
    });

    if (nextBtn) nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoplay();
    });

    // Start autoplay
    startAutoplay();

    // Pause on hover
    heroCarousel.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
    heroCarousel.addEventListener('mouseleave', startAutoplay);
}

// Team Carousel - Infinite Scroll
const teamCarousel = document.getElementById('teamCarousel');
if (teamCarousel) {
    // Clone all team members to create infinite loop effect
    const teamMembers = Array.from(teamCarousel.children);
    teamMembers.forEach(member => {
        const clone = member.cloneNode(true);
        teamCarousel.appendChild(clone);
    });
}

// Clients Carousel - Infinite Scroll
const clientsCarousel = document.getElementById('clientsCarousel');
if (clientsCarousel) {
    // Clone all client logos to create infinite loop effect
    const clientLogos = Array.from(clientsCarousel.children);
    clientLogos.forEach(logo => {
        const clone = logo.cloneNode(true);
        clientsCarousel.appendChild(clone);
    });
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const offsetTop = target.offsetTop - 80;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

// Scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe sections for animation
document.querySelectorAll('section').forEach(section => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(30px)';
    section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
    observer.observe(section);
});

// Contact Form Handler
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        
        // Criar o corpo do email
        const emailBody = `Nome: ${name}%0D%0AEmail: ${email}%0D%0A%0D%0AMensagem:%0D%0A${message}`;
        
        // Abrir o cliente de email do utilizador
        window.location.href = `mailto:paulimane2000@gmail.com?subject=${encodeURIComponent(subject)}&body=${emailBody}`;
        
        // Mostrar mensagem de confirmação
        alert('O seu cliente de email será aberto. Por favor, envie a mensagem.');
        
        // Limpar o formulário
        contactForm.reset();
    });
}
