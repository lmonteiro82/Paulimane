<?php
/**
 * API - Atualizar Membro da Equipa
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
    if (empty($input['nome']) || empty($input['funcao']) || empty($input['imagem'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome, função e imagem são obrigatórios'
        ]);
        exit;
    }
    
    $id = (int)$input['id'];
    $nome = trim($input['nome']);
    $funcao = trim($input['funcao']);
    $imagem = trim($input['imagem']);
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        UPDATE Equipa 
        SET Imagem = :imagem, Nome = :nome, Funcao = :funcao
        WHERE ID = :id
    ");
    
    $stmt->execute([
        ':id' => $id,
        ':imagem' => $imagem,
        ':nome' => $nome,
        ':funcao' => $funcao
    ]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Membro atualizado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Update Equipa Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar membro'
    ]);
}
?>
