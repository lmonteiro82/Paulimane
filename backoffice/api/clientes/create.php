<?php
/**
 * API - Criar Cliente
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
    
    error_log("Create Cliente - Input recebido: " . json_encode($input));
    
    // Validar campos obrigatórios
    if (empty($input['name']) || empty($input['imagem'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome e imagem são obrigatórios',
            'debug' => $input
        ]);
        exit;
    }
    
    $name = trim($input['name']);
    $imagem = trim($input['imagem']);
    
    error_log("Create Cliente - Nome: $name, Imagem: $imagem");
    
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        INSERT INTO Clientes (imagem, Nome) 
        VALUES (:imagem, :name)
    ");
    
    $stmt->execute([
        ':imagem' => $imagem,
        ':name' => $name
    ]);
    
    $id = $db->lastInsertId();
    
    error_log("Create Cliente - Sucesso! ID: $id");
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Cliente adicionado com sucesso',
        'id' => $id
    ]);

} catch (Exception $e) {
    error_log("Create Cliente Error: " . $e->getMessage());
    error_log("Create Cliente Stack: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao adicionar cliente: ' . $e->getMessage()
    ]);
}
?>
