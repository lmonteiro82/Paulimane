/**
 * Controle de Acesso - Sidebar Dinâmica
 * Paulimane Backoffice
 */

// Verificar nível de acesso ao carregar
document.addEventListener('DOMContentLoaded', async () => {
    await checkUserAccess();
});

/**
 * Verifica o nível de acesso do usuário e ajusta a sidebar
 */
async function checkUserAccess() {
    try {
        // Buscar informações do usuário da sessão
        const response = await fetch('api/check-session.php');
        const data = await response.json();
        
        if (!data.success || !data.user) {
            // Público: não redirecionar
            return;
        }
        
        const nivel = data.user.nivel || 1;
        
        // Ajustar sidebar baseado no nível
        adjustSidebarByLevel(nivel);
        
        // Verificar se pode acessar a página atual
        checkCurrentPageAccess(nivel);
        
    } catch (error) {
        console.error('Erro ao verificar acesso:', error);
    }
}

/**
 * Ajusta a sidebar baseado no nível do usuário
 */
function adjustSidebarByLevel(nivel) {
    // Definir páginas por nível
    const paginasPorNivel = {
        1: ['textos', 'equipa', 'clientes'],
        2: ['textos', 'equipa', 'clientes', 'categorias', 'produtos', 'destaques'],
        3: ['textos', 'equipa', 'clientes', 'categorias', 'produtos', 'destaques', 'utilizadores']
    };
    
    const paginasPermitidas = paginasPorNivel[nivel] || paginasPorNivel[1];
    
    // Obter todos os links da sidebar
    const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        
        if (!href) return;
        
        // Extrair nome da página do href
        const pagina = href.replace('.php', '').replace('.html', '').split('/').pop();
        
        // Verificar se o usuário tem acesso
        if (!paginasPermitidas.includes(pagina)) {
            // Ocultar item da sidebar
            item.style.display = 'none';
        } else {
            // Mostrar item
            item.style.display = 'flex';
        }
    });
}

/**
 * Verifica se o usuário pode acessar a página atual
 */
function checkCurrentPageAccess(nivel) {
    const paginaAtual = window.location.pathname.split('/').pop().replace('.php', '').replace('.html', '');
    
    const nivelPorPagina = {
        'textos': 1,
        'equipa': 1,
        'clientes': 1,
        'categorias': 2,
        'produtos': 2,
        'destaques': 2,
        'utilizadores': 3
    };
    
    const nivelNecessario = nivelPorPagina[paginaAtual];
    
    // Se a página requer um nível específico
    if (nivelNecessario) {
        // Público: não redirecionar
        return;
    }
}

/**
 * Verifica se o usuário tem permissão para uma ação específica
 */
function hasPermission(action) {
    // Buscar nível do localStorage (salvo no login)
    const userDataStr = localStorage.getItem('user_data');
    if (!userDataStr) return false;
    
    try {
        const userData = JSON.parse(userDataStr);
        const nivel = userData.nivel || 1;
        
        const permissoes = {
            'editar_textos': 1,
            'editar_equipa': 1,
            'editar_clientes': 1,
            'editar_categorias': 2,
            'editar_produtos': 2,
            'editar_destaques': 2,
            'gerenciar_usuarios': 3
        };
        
        const nivelNecessario = permissoes[action];
        
        if (!nivelNecessario) return true; // Ação não definida, permitir
        
        // Nível 3 tem acesso a tudo
        return nivel === 3 || nivel >= nivelNecessario;
        
    } catch (error) {
        console.error('Erro ao verificar permissão:', error);
        return false;
    }
}

// Expor funções globalmente
window.hasPermission = hasPermission;
window.checkUserAccess = checkUserAccess;
