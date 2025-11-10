<?php
/**
 * API - Eliminar Cliente
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
    $stmt = $db->prepare("SELECT imagem FROM Clientes WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cliente && file_exists('../../' . $cliente['imagem'])) {
        unlink('../../' . $cliente['imagem']);
    }
    
    // Deletar da BD
    $stmt = $db->prepare("DELETE FROM Clientes WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Cliente eliminado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Delete Cliente Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao eliminar cliente'
    ]);
}
?>
