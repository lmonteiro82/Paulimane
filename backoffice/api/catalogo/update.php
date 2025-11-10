<?php
/**
 * API - Atualizar Item do Catálogo
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
    if (empty($input['nome']) || empty($input['imagem'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome e imagem são obrigatórios'
        ]);
        exit;
    }
    
    $id = (int)$input['id'];
    $nome = trim($input['nome']);
    $descricao = isset($input['descricao']) ? trim($input['descricao']) : '';
    $imagem = trim($input['imagem']);
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        UPDATE Categoria 
        SET Imagem = :imagem, Nome = :nome, Descricao = :descricao
        WHERE ID = :id
    ");
    
    $stmt->execute([
        ':id' => $id,
        ':imagem' => $imagem,
        ':nome' => $nome,
        ':descricao' => $descricao
    ]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Item do catálogo atualizado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Update Catalogo Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar item do catálogo'
    ]);
}
?>
