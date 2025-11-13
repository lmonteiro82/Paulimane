<?php
/**
 * API - Deletar Destaque
 */

session_start();

header('Content-Type: application/json');

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
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validar ID
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID é obrigatório'
        ]);
        exit;
    }
    
    $id = (int)$input['id'];
    
    $db = getDBConnection();
    
    // Verificar se o destaque existe
    $stmt = $db->prepare("SELECT ID FROM Destaques WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Destaque não encontrado'
        ]);
        exit;
    }
    
    // Deletar destaque
    $stmt = $db->prepare("DELETE FROM Destaques WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Destaque removido com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Delete Destaque Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao remover destaque'
    ]);
}
?>
