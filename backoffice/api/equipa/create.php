<?php
/**
 * API - Criar Membro da Equipa
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
    
    // Validar campos obrigatórios
    if (empty($input['nome']) || empty($input['funcao']) || empty($input['imagem'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome, função e imagem são obrigatórios'
        ]);
        exit;
    }
    
    $nome = trim($input['nome']);
    $funcao = trim($input['funcao']);
    $imagem = trim($input['imagem']);
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        INSERT INTO Equipa (Imagem, Nome, Funcao) 
        VALUES (:imagem, :nome, :funcao)
    ");
    
    $stmt->execute([
        ':imagem' => $imagem,
        ':nome' => $nome,
        ':funcao' => $funcao
    ]);
    
    $id = $db->lastInsertId();
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Membro criado com sucesso',
        'id' => $id
    ]);

} catch (Exception $e) {
    error_log("Create Equipa Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao criar membro'
    ]);
}
?>
