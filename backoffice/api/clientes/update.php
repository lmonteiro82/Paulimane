<?php
/**
 * API - Atualizar Cliente
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
    
    // Validar campos obrigatórios
    if (empty($input['name']) || empty($input['imagem'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome e imagem são obrigatórios'
        ]);
        exit;
    }
    
    $id = (int)$input['id'];
    $name = trim($input['name']);
    $imagem = trim($input['imagem']);
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        UPDATE Clientes 
        SET imagem = :imagem, Nome = :name
        WHERE ID = :id
    ");
    
    $stmt->execute([
        ':id' => $id,
        ':imagem' => $imagem,
        ':name' => $name
    ]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Cliente atualizado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Update Cliente Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar cliente'
    ]);
}
?>
