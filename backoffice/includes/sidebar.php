<?php
/**
 * Sidebar Dinâmica com Controle de Acesso
 * Mostra apenas links permitidos baseado no nível do usuário
 */

// Obter nível do usuário
$nivel_usuario = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 1;

// Definir quais páginas cada nível pode acessar
$paginas_nivel = [
    1 => ['textos', 'equipa', 'clientes'],
    2 => ['textos', 'equipa', 'clientes', 'categorias', 'destaques'],
    3 => ['utilizadores', 'textos', 'equipa', 'clientes', 'categorias', 'destaques']
];

$paginas_permitidas = $paginas_nivel[$nivel_usuario] ?? $paginas_nivel[1];

// Obter página atual
$pagina_atual = basename($_SERVER['PHP_SELF'], '.php');

/**
 * Verifica se o usuário pode ver um link
 */
function podeVerLink($pagina, $paginas_permitidas) {
    return in_array($pagina, $paginas_permitidas);
}
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="../images/logo.png" alt="Paulimane Logo" class="sidebar-logo">
        <h2>Paulimane</h2>
    </div>
    
    <nav class="sidebar-nav">
        <?php if (podeVerLink('utilizadores', $paginas_permitidas)): ?>
        <a href="utilizadores.php" class="nav-item <?php echo $pagina_atual === 'utilizadores' ? 'active' : ''; ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Utilizadores</span>
        </a>
        <?php endif; ?>
        
        <?php if (podeVerLink('textos', $paginas_permitidas)): ?>
        <a href="textos.php" class="nav-item <?php echo $pagina_atual === 'textos' ? 'active' : ''; ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            <span>Sobre Nós</span>
        </a>
        <?php endif; ?>

        <?php if (podeVerLink('equipa', $paginas_permitidas)): ?>
        <a href="equipa.php" class="nav-item <?php echo $pagina_atual === 'equipa' ? 'active' : ''; ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Equipa</span>
        </a>
        <?php endif; ?>

        <?php if (podeVerLink('clientes', $paginas_permitidas)): ?>
        <a href="clientes.php" class="nav-item <?php echo $pagina_atual === 'clientes' ? 'active' : ''; ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Clientes</span>
        </a>
        <?php endif; ?>

        <?php if (podeVerLink('categorias', $paginas_permitidas)): ?>
        <a href="categorias.php" class="nav-item <?php echo $pagina_atual === 'categorias' ? 'active' : ''; ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            <span>Categorias</span>
        </a>
        <?php endif; ?>

        <?php if (podeVerLink('destaques', $paginas_permitidas)): ?>
        <a href="destaques.php" class="nav-item <?php echo $pagina_atual === 'destaques' ? 'active' : ''; ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            <span>Destaques</span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <button class="logout-btn" id="logoutBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Sair</span>
        </button>
    </div>
</aside>
