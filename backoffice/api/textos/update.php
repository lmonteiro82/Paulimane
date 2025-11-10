<?php
/**
 * API - Atualizar Texto
 * Paulimane Backoffice
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

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
    // Ler dados do request
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validar campos
    if (empty($input['chave']) || empty($input['texto'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Chave e texto são obrigatórios'
        ]);
        exit;
    }
    
    $chave = trim($input['chave']);
    $texto = trim($input['texto']);
    
    // Validar tamanho
    if (strlen($texto) > 2000) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'O texto excede o limite de 2000 caracteres'
        ]);
        exit;
    }
    
    $db = getDBConnection();
    
    // Verificar se o texto já existe
    $stmt = $db->prepare("SELECT ID FROM Textos WHERE Chave = :chave");
    $stmt->execute([':chave' => $chave]);
    $exists = $stmt->fetch();
    
    if ($exists) {
        // Atualizar texto existente
        $stmt = $db->prepare("UPDATE Textos SET Texto = :texto WHERE Chave = :chave");
        $stmt->execute([
            ':texto' => $texto,
            ':chave' => $chave
        ]);
    } else {
        // Inserir novo texto
        $stmt = $db->prepare("INSERT INTO Textos (Chave, Texto) VALUES (:chave, :texto)");
        $stmt->execute([
            ':chave' => $chave,
            ':texto' => $texto
        ]);
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Texto atualizado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Update Texto Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar texto',
        'debug' => $e->getMessage() // REMOVER EM PRODUÇÃO
    ]);
}
?>
