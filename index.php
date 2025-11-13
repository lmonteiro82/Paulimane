<?php
// Carregar configuração da base de dados
require_once 'backoffice/config/database.php';

// Buscar texto do Sobre Nós
$sobreNosTexto = '';
try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT Texto FROM Textos WHERE Chave = 'sobrenos' LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $sobreNosTexto = $result['Texto'];
    } else {
        // Texto padrão caso não exista na BD
        $sobreNosTexto = "A Paulimane - Ferragens Manuel Carmo & Azevedo, Lda é uma empresa portuguesa dedicada à comercialização de ferragens e tubagens de alta qualidade desde o ano 2000.\n\nCom mais de duas décadas de experiência no mercado, especializamo-nos em fornecer soluções completas em tubagens industriais e ferragens para os mais diversos sectores, sempre com foco na excelência e satisfação dos nossos clientes.\n\nA nossa missão é oferecer produtos de qualidade superior, aliados a um serviço personalizado e profissional, garantindo que cada cliente encontre exatamente o que precisa para os seus projetos.";
    }
} catch (Exception $e) {
    error_log("Erro ao carregar texto: " . $e->getMessage());
    $sobreNosTexto = "A Paulimane - Ferragens Manuel Carmo & Azevedo, Lda é uma empresa portuguesa dedicada à comercialização de ferragens e tubagens de alta qualidade desde o ano 2000.\n\nCom mais de duas décadas de experiência no mercado, especializamo-nos em fornecer soluções completas em tubagens industriais e ferragens para os mais diversos sectores, sempre com foco na excelência e satisfação dos nossos clientes.\n\nA nossa missão é oferecer produtos de qualidade superior, aliados a um serviço personalizado e profissional, garantindo que cada cliente encontre exatamente o que precisa para os seus projetos.";
}

// Dividir o texto em parágrafos
$paragrafos = explode("\n\n", $sobreNosTexto);

// Buscar estatísticas
$estatisticas = [
    'numero1' => '23+',
    'numero2' => '500+',
    'numero3' => '100%',
    'numero_texto1' => 'Anos de Experiência',
    'numero_texto2' => 'Clientes Satisfeitos',
    'numero_texto3' => 'Qualidade Garantida'
];

try {
    $chaves = ['numero1', 'numero2', 'numero3', 'numero_texto1', 'numero_texto2', 'numero_texto3'];
    foreach ($chaves as $chave) {
        $stmt = $db->prepare("SELECT Texto FROM Textos WHERE Chave = :chave LIMIT 1");
        $stmt->execute([':chave' => $chave]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $estatisticas[$chave] = $result['Texto'];
        }
    }
} catch (Exception $e) {
    error_log("Erro ao carregar estatísticas: " . $e->getMessage());
}

// Buscar membros da equipa
$equipaMembers = [];
try {
    $stmt = $db->query("SELECT Imagem, Nome, Funcao FROM Equipa ORDER BY ID ASC");
    $equipaMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Erro ao carregar equipa: " . $e->getMessage());
}

// Buscar clientes
$clientes = [];
try {
    $stmt = $db->query("SELECT imagem, Nome FROM Clientes ORDER BY ID ASC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Erro ao carregar clientes: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>Paulimane - Ferragens Manuel Carmo & Azevedo | Ferragens de Qualidade desde 2000</title>
    <meta name="description" content="Paulimane - Ferragens Manuel Carmo & Azevedo, Lda. Empresa portuguesa especializada em ferragens de alta qualidade desde 2000. Soluções completas para os mais diversos sectores.">
    <meta name="keywords" content="paulimane, ferragens, ferragens portugal, manuel carmo azevedo, ferragens qualidade, ferragens industriais, comercio ferragens">
    <meta name="author" content="Paulimane - Ferragens Manuel Carmo & Azevedo, Lda">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Portuguese">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.paulimane.pt/">
    <meta property="og:title" content="Paulimane - Ferragens Manuel Carmo & Azevedo">
    <meta property="og:description" content="Empresa portuguesa especializada em ferragens de alta qualidade desde 2000. Soluções completas para os mais diversos sectores.">
    <meta property="og:image" content="https://www.paulimane.pt/images/logo.png">
    <meta property="og:locale" content="pt_PT">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.paulimane.pt/">
    <meta property="twitter:title" content="Paulimane - Ferragens Manuel Carmo & Azevedo">
    <meta property="twitter:description" content="Empresa portuguesa especializada em ferragens de alta qualidade desde 2000.">
    <meta property="twitter:image" content="https://www.paulimane.pt/images/logo.png">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.paulimane.pt/">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logo.png?v=2" type="image/png">
    
    <!-- Sistema de Proteção - DEVE SER O PRIMEIRO SCRIPT -->
    <script src="js/auth-protection.js"></script>
    
    <!-- Structured Data (Schema.org) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Paulimane - Ferragens Manuel Carmo & Azevedo, Lda",
        "image": "https://www.paulimane.pt/images/logo.png",
        "description": "Empresa portuguesa especializada em ferragens de alta qualidade desde 2000. Soluções completas para os mais diversos sectores.",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "PT"
        },
        "url": "https://www.paulimane.pt",
        "telephone": "+351-XXX-XXX-XXX",
        "priceRange": "$$",
        "foundingDate": "2000",
        "openingHours": "Mo-Fr 09:00-18:00"
    }
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="index.php">
                        <img src="images/logo.png" alt="Paulimane Logo">
                    </a>
                </div>
                <div class="nav-menu" id="navMenu">
                    <a href="#inicio" class="nav-link">Início</a>
                    <a href="#sobre" class="nav-link">Sobre Nós</a>
                    <a href="#equipa" class="nav-link">Equipa</a>
                    <a href="#clientes" class="nav-link">Clientes</a>
                    <a href="#contacto" class="nav-link">Contacto</a>
                    <a href="catalogo.html" class="nav-btn">Catálogo</a>
                </div>
                <button class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <section id="inicio" class="hero-section">
        <div class="carousel">
            <div class="carousel-track" id="heroCarousel">
                <!-- Slide 1: Vídeo -->
                <div class="carousel-slide active">
                    <!-- Vídeo temporariamente desativado
                    <video class="hero-video" autoplay muted loop playsinline>
                        <source src="images/hero-video.mp4" type="video/mp4">
                        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1600&h=900&fit=crop" alt="Paulimane">
                    </video>
                    -->
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1600&h=900&fit=crop" alt="Paulimane">
                    <div class="carousel-overlay carousel-overlay-left">
                        <div class="carousel-content carousel-content-left">
                            <h1 class="hero-title-large">NOVO CATÁLOGO<br>ONLINE</h1>
                            <p class="hero-subtitle-large">A melhor seleção em ferragens</p>
                            <a href="catalogo.html" class="hero-cta-btn">
                                <span class="cta-icon">→</span>
                                <span>VER CATÁLOGO</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Slide 2: Imagem -->
                <div class="carousel-slide">
                    <img src="https://scontent.fopo5-1.fna.fbcdn.net/v/t39.30808-6/489357416_1214569440671006_6624947103517476282_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=cc71e4&_nc_ohc=qyXsTYsW5uEQ7kNvwGJCScw&_nc_oc=AdncMjun8JqEbLeX5CPyd26f48g6N6EXng1SoN432OVU7RORPpebQftcOu-HdIJNxZvCkdZz-N7ToaSEfemGLWWz&_nc_zt=23&_nc_ht=scontent.fopo5-1.fna&_nc_gid=HpEK8ls2EY2a8a6JswjWDg&oh=00_AfdMmGq-ikvP5cl8BJDYzxCj2dt6lnyU-cYFRkA5VZqiuQ&oe=69095F49" alt="Ferragens profissionais">
                    <div class="carousel-overlay carousel-overlay-left">
                        <div class="carousel-content carousel-content-left">
                            <h1 class="hero-title">Qualidade Superior</h1>
                            <p class="hero-subtitle">Ferragens e tubagens de excelência</p>
                            <p class="hero-year">Mais de 23 anos de experiência</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 3: Imagem -->
                <div class="carousel-slide">
                    <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=1920&h=800&fit=crop" alt="Soluções Paulimane">
                    <div class="carousel-overlay carousel-overlay-left">
                        <div class="carousel-content carousel-content-left">
                            <h1 class="hero-title">Soluções Completas</h1>
                            <p class="hero-subtitle">Para todos os seus projetos</p>
                            <p class="hero-year">Confiança e profissionalismo</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="carousel-indicators" id="indicators"></div>
        </div>
    </section>

    <!-- Sobre Nós -->
    <section id="sobre" class="about-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Sobre Nós</h2>
                <div class="section-divider"></div>
            </div>
            <div class="about-content">
                <div class="about-card">
                    <?php foreach ($paragrafos as $paragrafo): ?>
                        <?php if (trim($paragrafo)): ?>
                            <p class="about-text"><?php echo nl2br(htmlspecialchars($paragrafo)); ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo htmlspecialchars($estatisticas['numero1']); ?></div>
                            <div class="stat-label"><?php echo htmlspecialchars($estatisticas['numero_texto1']); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo htmlspecialchars($estatisticas['numero2']); ?></div>
                            <div class="stat-label"><?php echo htmlspecialchars($estatisticas['numero_texto2']); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo htmlspecialchars($estatisticas['numero3']); ?></div>
                            <div class="stat-label"><?php echo htmlspecialchars($estatisticas['numero_texto3']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Equipa -->
    <section id="equipa" class="team-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nossa Equipa</h2>
                <div class="section-divider"></div>
                <p class="section-description">
                    Conheça os profissionais dedicados que fazem da Paulimane uma referência no sector
                </p>
            </div>
            <div class="team-carousel-wrapper">
                <div class="team-carousel-track">
                    <div class="team-carousel" id="teamCarousel">
                        <?php if (empty($equipaMembers)): ?>
                            <p style="text-align: center; color: #999; width: 100%;">Nenhum membro da equipa adicionado</p>
                        <?php else: ?>
                            <?php foreach ($equipaMembers as $member): ?>
                                <div class="team-member">
                                    <div class="team-image">
                                        <?php 
                                        $imagePath = $member['Imagem'];
                                        // Garantir que o caminho começa com /
                                        if (!empty($imagePath) && $imagePath[0] !== '/') {
                                            $imagePath = '/' . $imagePath;
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($member['Nome']); ?>" onerror="this.src='https://via.placeholder.com/400'">
                                    </div>
                                    <h3 class="team-name"><?php echo htmlspecialchars($member['Nome']); ?></h3>
                                    <p class="team-role"><?php echo htmlspecialchars($member['Funcao']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Clientes -->
    <section id="clientes" class="clients-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nossos Clientes</h2>
                <div class="section-divider"></div>
                <p class="section-description">
                    Empresas que confiam na qualidade Paulimane
                </p>
            </div>
            <div class="clients-carousel-wrapper">
                <div class="clients-carousel-track">
                    <div class="clients-carousel" id="clientsCarousel">
                        <?php if (empty($clientes)): ?>
                            <p style="text-align: center; color: #999; width: 100%;">Nenhum cliente adicionado</p>
                        <?php else: ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <div class="client-logo">
                                    <?php 
                                    $imagePath = $cliente['imagem'];
                                    // Garantir que o caminho começa com /
                                    if (!empty($imagePath) && $imagePath[0] !== '/') {
                                        $imagePath = '/' . $imagePath;
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($cliente['Nome']); ?>" onerror="this.src='https://via.placeholder.com/200x100'">
                                    <p class="client-name"><?php echo htmlspecialchars($cliente['Nome']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Produtos em Destaque -->
    <section class="featured-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Produtos em Destaque</h2>
                <div class="section-divider"></div>
                <p class="section-description">Alguns produtos do nosso catálogo</p>
            </div>

            <div id="featuredProducts">
                <!-- Produtos em destaque serão carregados dinamicamente -->
                <div style="text-align: center; padding: 60px 20px; color: #999;">
                    <p>A carregar produtos em destaque...</p>
                </div>
            </div>

            <div class="featured-cta">
                <a href="catalogo.html" class="nav-btn">Ver Catálogo Completo</a>
            </div>
        </div>
    </section>

    <!-- Contact Us -->
    <section id="contacto" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Contacte-nos</h2>
                <div class="section-divider"></div>
                <p class="section-description">
                    Tem alguma questão? Envie-nos uma mensagem e entraremos em contacto consigo
                </p>
            </div>
            <div class="contact-content">
                <form class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Nome *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject">Assunto *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Mensagem *</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        <span>Enviar Mensagem</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="images/logo.png" alt="Paulimane" class="footer-logo">
                    <p class="footer-text">
                        A melhor seleção em tubagens desde 2000
                    </p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/paulimane2000/" target="_blank" rel="noopener noreferrer" class="social-link" title="Siga-nos no Facebook">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3 class="footer-title">Contactos</h3>
                    <p class="footer-text">Email: paulimane2000@gmail.com</p>
                    <p class="footer-text">Telefone: 22 744 0671</p>
                </div>
                <div class="footer-section">
                    <h3 class="footer-title">Navegação</h3>
                    <a href="#inicio" class="footer-link">Início</a>
                    <a href="#sobre" class="footer-link">Sobre Nós</a>
                    <a href="#equipa" class="footer-link">Equipa</a>
                    <a href="#contacto" class="footer-link">Contacto</a>
                    <a href="catalogo.html" class="footer-link">Catálogo</a>
                </div>
                <div class="footer-section">
                    <h3 class="footer-title">Horário</h3>
                    <p class="footer-text">Segunda - Sexta: 9h - 18h</p>
                    <p class="footer-text">Sábado: 9h - 13h</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Paulimane - Ferragens Manuel Carmo & Azevedo, Lda. Todos os direitos reservados.</p>
                <a href="backoffice/login.html" class="backoffice-link" title="Acesso ao Backoffice">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
    <!-- Back to top button -->
    <button class="back-to-top" id="backToTop" aria-label="Voltar ao topo" title="Voltar ao topo">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>

    <script src="js/main.js"></script>
</body>
</html>
