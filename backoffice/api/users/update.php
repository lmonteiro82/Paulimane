<?php
/**
 * API - Atualizar Utilizador
 * Paulimane Backoffice
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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
    
    // Validar campos obrigatórios
    if (empty($input['id']) || empty($input['nome']) || empty($input['email'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, preencha todos os campos obrigatórios'
        ]);
        exit;
    }
    
    $id = (int)$input['id'];
    $nome = trim($input['nome']);
    $email = trim($input['email']);
    $ativo = isset($input['ativo']) ? (int)$input['ativo'] : 1;
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Email inválido'
        ]);
        exit;
    }
    
    $db = getDBConnection();
    
    // Verificar se utilizador existe
    $stmt = $db->prepare("SELECT ID FROM Utilizador WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Utilizador não encontrado'
        ]);
        exit;
    }
    
    // Verificar se email já existe (exceto para o próprio utilizador)
    $stmt = $db->prepare("SELECT ID FROM Utilizador WHERE Email = :email AND ID != :id");
    $stmt->execute([':email' => $email, ':id' => $id]);
    
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Já existe outro utilizador com este email'
        ]);
        exit;
    }
    
    // Atualizar utilizador
    if (!empty($input['password'])) {
        // Se password foi fornecida, atualizar também
        if (strlen($input['password']) < 6) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'A password deve ter no mínimo 6 caracteres'
            ]);
            exit;
        }
        
        $hashedPassword = password_hash($input['password'], PASSWORD_BCRYPT);
        
        $stmt = $db->prepare("
            UPDATE Utilizador 
            SET Nome = :nome, Email = :email, Password = :password, Ativo = :ativo 
            WHERE ID = :id
        ");
        
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':ativo' => $ativo,
            ':id' => $id
        ]);
    } else {
        // Atualizar sem alterar password
        $stmt = $db->prepare("
            UPDATE Utilizador 
            SET Nome = :nome, Email = :email, Ativo = :ativo 
            WHERE ID = :id
        ");
        
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':ativo' => $ativo,
            ':id' => $id
        ]);
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Utilizador atualizado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Update User Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar utilizador'
    ]);
}
?>
