<?php
/**
 * API de Categorias - Backoffice
 * Listar categorias para uso em selects
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

require_once '../config/database.php';

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

try {
    $db = getDBConnection();
    
    // Listar todas as categorias
    $stmt = $db->query("SELECT ID, Nome, Descricao, Imagem FROM Categoria ORDER BY Nome ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $categorias
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("API Categorias Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar categorias'
    ]);
}
?>
