<?php
/**
 * API - Adicionar Produto em Destaque
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
    
    // Validar campo obrigatório
    if (empty($input['produtoId'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID do produto é obrigatório'
        ]);
        exit;
    }
    
    $produtoId = (int)$input['produtoId'];
    
    $db = getDBConnection();
    
    // Verificar se o produto existe
    $stmt = $db->prepare("SELECT ID FROM Produtos WHERE ID = :id");
    $stmt->execute([':id' => $produtoId]);
    if (!$stmt->fetch()) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Produto não encontrado'
        ]);
        exit;
    }
    
    // Verificar se já está em destaque
    $stmt = $db->prepare("SELECT ID FROM Destaques WHERE ProdutoID = :produtoId");
    $stmt->execute([':produtoId' => $produtoId]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Este produto já está em destaque'
        ]);
        exit;
    }
    
    // Verificar limite de 6 produtos em destaque
    $stmt = $db->query("SELECT COUNT(*) as total FROM Destaques");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['total'] >= 6) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Limite de 6 produtos em destaque atingido. Remova um produto antes de adicionar outro.'
        ]);
        exit;
    }
    
    // Adicionar produto em destaque
    $stmt = $db->prepare("INSERT INTO Destaques (ProdutoID) VALUES (:produtoId)");
    $stmt->execute([':produtoId' => $produtoId]);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Produto adicionado aos destaques com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Add Destaque Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao adicionar produto em destaque'
    ]);
}
?>
