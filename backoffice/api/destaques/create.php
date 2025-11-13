<?php
/**
 * API - Criar Destaque
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
    if (empty($input['nome']) || empty($input['imagem'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nome e imagem são obrigatórios'
        ]);
        exit;
    }
    
    $db = getDBConnection();
    
    // Verificar limite de 6 destaques
    $stmt = $db->query("SELECT COUNT(*) as total FROM Destaques");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['total'] >= 6) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Limite de 6 destaques atingido. Remova um destaque antes de adicionar outro.'
        ]);
        exit;
    }
    
    $nome = trim($input['nome']);
    $descricao = isset($input['descricao']) ? trim($input['descricao']) : '';
    $imagem = trim($input['imagem']);
    
    $stmt = $db->prepare("
        INSERT INTO Destaques (Imagem, Nome, Descricao) 
        VALUES (:imagem, :nome, :descricao)
    ");
    
    $stmt->execute([
        ':imagem' => $imagem,
        ':nome' => $nome,
        ':descricao' => $descricao
    ]);
    
    $id = $db->lastInsertId();
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Destaque adicionado com sucesso',
        'id' => $id
    ]);

} catch (Exception $e) {
    error_log("Create Destaque Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao adicionar destaque'
    ]);
}
?>
