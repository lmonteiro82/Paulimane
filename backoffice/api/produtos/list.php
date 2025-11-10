<?php
/**
 * API - Listar Produtos
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
    
    // Buscar produtos com nome da categoria
    $stmt = $db->query("
        SELECT p.ID, p.Imagem, p.Nome, p.Descricao, p.CategoriaID, c.Nome as CategoriaNome
        FROM Produtos p
        LEFT JOIN Categoria c ON p.CategoriaID = c.ID
        ORDER BY p.ID DESC
    ");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $produtos
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("List Produtos Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar produtos'
    ]);
}
?>
