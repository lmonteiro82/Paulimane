<?php
/**
 * API - Criar Utilizador
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
    
    // Validar campos obrigatórios
    if (empty($input['nome']) || empty($input['email']) || empty($input['password'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, preencha todos os campos obrigatórios'
        ]);
        exit;
    }
    
    $nome = trim($input['nome']);
    $email = trim($input['email']);
    $password = $input['password'];
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
    
    // Validar password
    if (strlen($password) < 6) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'A password deve ter no mínimo 6 caracteres'
        ]);
        exit;
    }
    
    $db = getDBConnection();
    
    // Verificar se email já existe
    $stmt = $db->prepare("SELECT ID FROM Utilizador WHERE Email = :email");
    $stmt->execute([':email' => $email]);
    
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Já existe um utilizador com este email'
        ]);
        exit;
    }
    
    // Encriptar password com hash
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Inserir utilizador
    $stmt = $db->prepare("
        INSERT INTO Utilizador (Nome, Email, Password, Ativo) 
        VALUES (:nome, :email, :password, :ativo)
    ");
    
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':ativo' => $ativo
    ]);
    
    $userId = $db->lastInsertId();
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Utilizador criado com sucesso',
        'user_id' => $userId
    ]);

} catch (Exception $e) {
    error_log("Create User Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao criar utilizador'
    ]);
}
?>
