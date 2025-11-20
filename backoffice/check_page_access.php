<?php
/**
 * Verificação de Acesso para Páginas HTML
 * Incluir no início de cada página HTML do backoffice
 */

session_start();

// Acesso público: não redirecionar para login quando não autenticado

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

// Acesso público: não redirecionar em caso de nível insuficiente
?>
