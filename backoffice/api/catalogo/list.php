<?php
/**
 * API - Listar Catálogo
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
    
    $stmt = $db->query("SELECT ID, Imagem, Nome, Descricao FROM Categoria ORDER BY ID ASC");
    $catalogo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Garantir que os caminhos das imagens começam com /
    foreach ($catalogo as &$item) {
        if (!empty($item['Imagem']) && $item['Imagem'][0] !== '/') {
            $item['Imagem'] = '/' . $item['Imagem'];
        }
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $catalogo
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("List Catalogo Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar catálogo'
    ]);
}
?>
