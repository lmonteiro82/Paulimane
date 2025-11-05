<?php
/**
 * API de Login
 * Paulimane Backoffice
 */

// Headers para CORS e JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Iniciar sessão
session_start();

// Incluir configuração da base de dados
require_once '../config/database.php';

// Verificar se é método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
    exit;
}

// Obter dados JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validar dados recebidos
if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Username e password são obrigatórios'
    ]);
    exit;
}

$username = trim($data['username']);
$password = $data['password'];
$rememberMe = isset($data['rememberMe']) ? $data['rememberMe'] : false;

// Validar campos vazios
if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Por favor, preencha todos os campos'
    ]);
    exit;
}

try {
    // Conectar à base de dados
    $db = getDBConnection();
    
    if (!$db) {
        throw new Exception('Erro ao conectar à base de dados');
    }

    // Preparar query para buscar utilizador (usando Email como username)
    $stmt = $db->prepare("
        SELECT ID, Nome, Email, Password, Ativo 
        FROM Utilizador 
        WHERE Email = :email 
        LIMIT 1
    ");
    
    $stmt->bindParam(':email', $username, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch();

    // Verificar se utilizador existe
    if (!$user) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Utilizador ou password incorretos'
        ]);
        exit;
    }

    // Verificar se utilizador está ativo
    if ($user['Ativo'] != 1) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Utilizador inativo. Contacte o administrador.'
        ]);
        exit;
    }

    // Verificar password (aceita texto simples ou hash)
    $password_valid = false;
    
    // Verificar se é hash (começa com $2y$)
    if (strpos($user['Password'], '$2y$') === 0) {
        // Password em hash - usar password_verify
        $password_valid = password_verify($password, $user['Password']);
    } else {
        // Password em texto simples - comparação direta
        $password_valid = ($password === $user['Password']);
    }
    
    if (!$password_valid) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Utilizador ou password incorretos'
        ]);
        exit;
    }

    // Login bem-sucedido!

    // Gerar token de sessão simples
    $token = bin2hex(random_bytes(32));

    // Guardar dados na sessão PHP
    $_SESSION['user_id'] = $user['ID'];
    $_SESSION['email'] = $user['Email'];
    $_SESSION['nome'] = $user['Nome'];
    $_SESSION['token'] = $token;
    $_SESSION['login_time'] = time();

    // Responder com sucesso
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Login realizado com sucesso',
        'data' => [
            'token' => $token,
            'user' => [
                'id' => $user['ID'],
                'email' => $user['Email'],
                'nome' => $user['Nome']
            ]
        ]
    ]);

} catch (Exception $e) {
    error_log("Login Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor. Tente novamente mais tarde.'
    ]);
}

?>
