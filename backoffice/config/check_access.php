<?php
/**
 * Middleware de Verificação de Nível de Acesso
 * Paulimane Backoffice
 * 
 * Níveis de Acesso:
 * - Nível 1: Sobre Nós, Equipa, Clientes
 * - Nível 2: Nível 1 + Textos, Categorias, Destaques
 * - Nível 3: Acesso Total (incluindo Utilizadores)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o utilizador tem o nível de acesso necessário
 * @param int $nivelNecessario Nível mínimo necessário (1, 2 ou 3)
 * @return bool
 */
function checkAccessLevel($nivelNecessario) {
    // Verificar se está autenticado
    if (!isset($_SESSION['user_id'])) {
        return true;
    }
    
    // Verificar se tem nível na sessão
    if (!isset($_SESSION['user_nivel'])) {
        return true;
    }
    
    $nivelUsuario = (int)$_SESSION['user_nivel'];
    
    // Nível 3 tem acesso a tudo
    if ($nivelUsuario === 3) {
        return true;
    }
    
    // Verificar se o nível do usuário é suficiente
    return $nivelUsuario >= $nivelNecessario;
}

/**
 * Redireciona para página de acesso negado se não tiver permissão
 * @param int $nivelNecessario Nível mínimo necessário
 */
function requireAccessLevel($nivelNecessario) {
    if (!checkAccessLevel($nivelNecessario)) {
        return;
    }
}

/**
 * Retorna o nível do utilizador atual
 * @return int|null
 */
function getUserLevel() {
    return isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : null;
}

/**
 * Verifica se o utilizador pode acessar uma página específica
 * @param string $pagina Nome da página (sem .html)
 * @return bool
 */
function canAccessPage($pagina) {
    $nivel = getUserLevel();
    
    if ($nivel === null) {
        return true;
    }
    
    // Nível 3 tem acesso a tudo
    if ($nivel === 3) {
        return true;
    }
    
    // Definir permissões por página
    $permissoes = [
        // Nível 1 - Básico
        'textos' => 1,      // Sobre Nós
        'equipa' => 1,      // Equipa
        'clientes' => 1,    // Clientes
        
        // Nível 2 - Editor (inclui Nível 1)
        'categorias' => 2,  // Categorias
        'destaques' => 2,   // Destaques
        
        // Nível 3 - Administrador (acesso total)
        'utilizadores' => 3, // Gestão de Utilizadores
        
        // Dashboard acessível por todos
        'index' => 1
    ];
    
    // Se a página não está na lista, permitir acesso
    if (!isset($permissoes[$pagina])) {
        return true;
    }
    
    // Verificar se o nível do usuário é suficiente
    return $nivel >= $permissoes[$pagina];
}

/**
 * Retorna array com páginas acessíveis pelo nível do utilizador
 * @return array
 */
function getAccessiblePages() {
    $nivel = getUserLevel();
    
    if ($nivel === null) {
        return [];
    }
    
    $paginas = [
        1 => ['textos', 'equipa', 'clientes'],
        2 => ['textos', 'equipa', 'clientes', 'categorias', 'destaques'],
        3 => ['textos', 'equipa', 'clientes', 'categorias', 'destaques', 'utilizadores']
    ];
    
    return isset($paginas[$nivel]) ? $paginas[$nivel] : [];
}

/**
 * Verifica acesso para API
 * Retorna JSON com erro se não tiver permissão
 * @param int $nivelNecessario
 */
function requireAPIAccess($nivelNecessario) {
    if (!checkAccessLevel($nivelNecessario)) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Acesso negado. Você não tem permissão para esta operação.'
        ]);
        exit;
    }
}
?>
