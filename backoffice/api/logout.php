<?php
/**
 * API de Logout
 * Paulimane Backoffice
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

require_once '../config/database.php';

try {
    // Destruir sessão
    session_destroy();
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Logout realizado com sucesso'
    ]);
    
} catch (Exception $e) {
    error_log("Logout Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao fazer logout'
    ]);
}
?>
