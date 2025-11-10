<?php
/**
 * API - Eliminar Membro da Equipa
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
    
    // Buscar imagem para deletar
    $stmt = $db->prepare("SELECT Imagem FROM Equipa WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    $membro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($membro && file_exists('../../' . $membro['Imagem'])) {
        unlink('../../' . $membro['Imagem']);
    }
    
    // Deletar da BD
    $stmt = $db->prepare("DELETE FROM Equipa WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Membro eliminado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Delete Equipa Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao eliminar membro'
    ]);
}
?>
