<?php
/**
 * Página Inicial do Backoffice
 * Redireciona baseado no nível de acesso
 */
session_start();

// Acesso público: não redirecionar para login quando não autenticado

// Obter nível do usuário
$nivel = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 1;

// Redirecionar baseado no nível
switch ($nivel) {
    case 3:
        // Administrador - redirecionar para utilizadores
        header('Location: utilizadores.php');
        break;
    case 2:
        // Editor - redirecionar para categorias
        header('Location: categorias.php');
        break;
    case 1:
    default:
        // Básico - redirecionar para textos
        header('Location: textos.php');
        break;
}
exit;
?>
