<?php
/**
 * API - Criar Produto
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
    
    error_log("Create Produto - Input recebido: " . json_encode($input));
    
    // Validar campos obrigatórios
    if (empty($input['nome']) || empty($input['imagem']) || empty($input['categoriaId'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome, imagem e categoria são obrigatórios',
            'debug' => $input
        ]);
        exit;
    }
    
    $nome = trim($input['nome']);
    $descricao = isset($input['descricao']) ? trim($input['descricao']) : '';
    $imagem = trim($input['imagem']);
    $categoriaId = (int)$input['categoriaId'];
    
    error_log("Create Produto - Nome: $nome, Categoria: $categoriaId, Imagem: $imagem");
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) 
        VALUES (:imagem, :nome, :descricao, :categoriaId)
    ");
    
    $stmt->execute([
        ':imagem' => $imagem,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':categoriaId' => $categoriaId
    ]);
    
    $id = $db->lastInsertId();
    
    error_log("Create Produto - Sucesso! ID: $id");
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Produto adicionado com sucesso',
        'id' => $id
    ]);

} catch (Exception $e) {
    error_log("Create Produto Error: " . $e->getMessage());
    error_log("Create Produto Stack: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao adicionar produto: ' . $e->getMessage()
    ]);
}
?>
