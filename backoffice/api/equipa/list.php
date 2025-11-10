<?php
/**
 * API - Listar Membros da Equipa
 */

session_start();

header('Content-Type: application/json; charset=utf-8');

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Não autenticado'
    ]);
    exit;
}

require_once '../../config/database.php';

try {
    $db = getDBConnection();
    
    $stmt = $db->query("SELECT ID, Imagem, Nome, Funcao FROM Equipa ORDER BY ID ASC");
    $membros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $membros
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("List Equipa Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar membros da equipa'
    ]);
}
?>
