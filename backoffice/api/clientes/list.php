<?php
/**
 * API - Listar Clientes
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
    
    $stmt = $db->query("SELECT ID, imagem, Nome FROM Clientes ORDER BY ID ASC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Garantir que os caminhos das imagens começam com /
    foreach ($clientes as &$item) {
        if (!empty($item['imagem']) && $item['imagem'][0] !== '/') {
            $item['imagem'] = '/' . $item['imagem'];
        }
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $clientes
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("List Clientes Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar clientes'
    ]);
}
?>
