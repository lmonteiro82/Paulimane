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
        window.location.href = `mailto:suporte@paulimane.pt?subject=${encodeURIComponent(subject)}&body=${emailBody}`;
        
        // Mostrar mensagem de confirmação
        alert('O seu cliente de email será aberto. Por favor, envie a mensagem.');
        
        // Limpar o formulário
        contactForm.reset();
    });
}

// Carregar Produtos em Destaque
async function loadFeaturedProducts() {
    const container = document.getElementById('featuredProducts');
    if (!container) return;
    
    try {
        const response = await fetch('api/destaques.php');
        const result = await response.json();
        
        if (result.success && result.data.length > 0) {
            const products = result.data;
            
            // Renderizar produtos em mosaico (máximo 6)
            let html = '';
            
            if (products.length >= 3) {
                // Primeira linha: 1 grande + 2 pequenas
                html += '<div class="featured-mosaic">';
                html += `
                    <a href="catalogo.html" class="featured-card mosaic-left">
                        <img src="${products[0].Imagem}" alt="${products[0].Nome}" onerror="this.src='https://via.placeholder.com/1600x900?text=Sem+Imagem'">
                        <div class="featured-info">
                            <h3 class="featured-name">${products[0].Nome}</h3>
                            <p class="featured-desc">${products[0].Descricao || 'Produto de qualidade'}</p>
                        </div>
                    </a>
                    <div class="mosaic-right">
                        <a href="catalogo.html" class="featured-card mosaic-top">
                            <img src="${products[1].Imagem}" alt="${products[1].Nome}" onerror="this.src='https://via.placeholder.com/900x600?text=Sem+Imagem'">
                            <div class="featured-info">
                                <h3 class="featured-name">${products[1].Nome}</h3>
                                <p class="featured-desc">${products[1].Descricao || 'Produto de qualidade'}</p>
                            </div>
                        </a>
                        <a href="catalogo.html" class="featured-card mosaic-bottom">
                            <img src="${products[2].Imagem}" alt="${products[2].Nome}" onerror="this.src='https://via.placeholder.com/900x600?text=Sem+Imagem'">
                            <div class="featured-info">
                                <h3 class="featured-name">${products[2].Nome}</h3>
                                <p class="featured-desc">${products[2].Descricao || 'Produto de qualidade'}</p>
                            </div>
                        </a>
                    </div>
                </div>`;
            }
            
            if (products.length >= 6) {
                // Segunda linha: 2 pequenas + 1 grande
                html += '<div class="featured-mosaic featured-mosaic-alt" style="margin-top: 5%;">';
                html += `
                    <div class="mosaic-left-stack">
                        <a href="catalogo.html" class="featured-card mosaic-top">
                            <img src="${products[3].Imagem}" alt="${products[3].Nome}" onerror="this.src='https://via.placeholder.com/900x600?text=Sem+Imagem'">
                            <div class="featured-info">
                                <h3 class="featured-name">${products[3].Nome}</h3>
                                <p class="featured-desc">${products[3].Descricao || 'Produto de qualidade'}</p>
                            </div>
                        </a>
                        <a href="catalogo.html" class="featured-card mosaic-bottom">
                            <img src="${products[4].Imagem}" alt="${products[4].Nome}" onerror="this.src='https://via.placeholder.com/900x600?text=Sem+Imagem'">
                            <div class="featured-info">
                                <h3 class="featured-name">${products[4].Nome}</h3>
                                <p class="featured-desc">${products[4].Descricao || 'Produto de qualidade'}</p>
                            </div>
                        </a>
                    </div>
                    <a href="catalogo.html" class="featured-card mosaic-right-big">
                        <img src="${products[5].Imagem}" alt="${products[5].Nome}" onerror="this.src='https://via.placeholder.com/1600x900?text=Sem+Imagem'">
                        <div class="featured-info">
                            <h3 class="featured-name">${products[5].Nome}</h3>
                            <p class="featured-desc">${products[5].Descricao || 'Produto de qualidade'}</p>
                        </div>
                    </a>
                </div>`;
            }
            
            container.innerHTML = html;
        } else {
            container.innerHTML = '<p style="text-align: center; color: #666; padding: 40px;">Nenhum produto em destaque no momento.</p>';
        }
    } catch (error) {
        console.error('Erro ao carregar produtos em destaque:', error);
        container.innerHTML = '<p style="text-align: center; color: #dc3545; padding: 40px;">Erro ao carregar produtos. Por favor, tente novamente.</p>';
    }
}

// Carregar produtos em destaque ao carregar a página
document.addEventListener('DOMContentLoaded', loadFeaturedProducts);
