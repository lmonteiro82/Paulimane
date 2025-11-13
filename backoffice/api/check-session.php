<?php
/**
 * API - Verificar Sessão e Nível de Acesso
 * Paulimane Backoffice
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    // Verificar se está autenticado
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Não autenticado'
        ]);
        exit;
    }
    
    // Retornar dados do usuário
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'nome' => $_SESSION['nome'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'nivel' => isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 1
        ]
    ]);

} catch (Exception $e) {
    error_log("Check Session Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao verificar sessão'
    ]);
}
?>
