<?php
/**
 * Verificação de Acesso para Páginas HTML
 * Incluir no início de cada página HTML do backoffice
 */

session_start();

// Verificar se está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Verificar nível de acesso baseado na página atual
$pagina_atual = basename($_SERVER['PHP_SELF'], '.html');

// Definir níveis necessários por página
$niveis_pagina = [
    'textos' => 1,
    'equipa' => 1,
    'clientes' => 1,
    'categorias' => 2,
    'destaques' => 2,
    'utilizadores' => 3
];

// Obter nível do usuário
$nivel_usuario = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 1;

// Verificar se a página requer um nível específico
if (isset($niveis_pagina[$pagina_atual])) {
    $nivel_necessario = $niveis_pagina[$pagina_atual];
    
    // Nível 3 tem acesso a tudo
    if ($nivel_usuario < 3 && $nivel_usuario < $nivel_necessario) {
        header('Location: acesso-negado.html');
        exit;
    }
}
?>
