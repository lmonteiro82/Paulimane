<?php
/**
 * API - Criar Item do Catálogo
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
require_once '../../config/check_access.php';

// Verificar nível de acesso (nível 2 ou superior)
requireAPIAccess(2);

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    error_log("Create Catalogo - Input recebido: " . json_encode($input));
    
    // Validar campos obrigatórios
    if (empty($input['nome']) || empty($input['imagem']) || empty($input['pdf'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome, imagem e PDF são obrigatórios',
            'debug' => $input
        ]);
        exit;
    }
    
    $nome = trim($input['nome']);
    $descricao = isset($input['descricao']) ? trim($input['descricao']) : '';
    $imagem = trim($input['imagem']);
    $pdf = trim($input['pdf']);
    
    error_log("Create Catalogo - Nome: $nome, Descrição: $descricao, Imagem: $imagem, PDF: $pdf");
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        INSERT INTO Categoria (Imagem, Nome, Descricao, PDF) 
        VALUES (:imagem, :nome, :descricao, :pdf)
    ");
    
    $stmt->execute([
        ':imagem' => $imagem,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':pdf' => $pdf
    ]);
    
    $id = $db->lastInsertId();
    
    error_log("Create Catalogo - Sucesso! ID: $id");
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Item adicionado ao catálogo com sucesso',
        'id' => $id
    ]);

} catch (Exception $e) {
    error_log("Create Catalogo Error: " . $e->getMessage());
    error_log("Create Catalogo Stack: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao adicionar item ao catálogo: ' . $e->getMessage()
    ]);
}
?>
