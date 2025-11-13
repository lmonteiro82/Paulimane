<?php
/**
 * API - Atualizar Produto
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
    if (empty($input['nome']) || empty($input['imagem']) || empty($input['categoriaId'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome, imagem e categoria são obrigatórios'
        ]);
        exit;
    }
    
    $id = (int)$input['id'];
    $nome = trim($input['nome']);
    $descricao = isset($input['descricao']) ? trim($input['descricao']) : '';
    $imagem = trim($input['imagem']);
    $categoriaId = (int)$input['categoriaId'];
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        UPDATE Produtos 
        SET Imagem = :imagem, Nome = :nome, Descricao = :descricao, CategoriaID = :categoriaId
        WHERE ID = :id
    ");
    
    $stmt->execute([
        ':id' => $id,
        ':imagem' => $imagem,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':categoriaId' => $categoriaId
    ]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Produto atualizado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Update Produto Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar produto'
    ]);
}
?>
