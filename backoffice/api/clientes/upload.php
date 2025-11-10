<?php
/**
 * API - Upload de Imagem de Cliente
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

try {
    // Verificar se ficheiro foi enviado
    if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nenhuma imagem foi enviada'
        ]);
        exit;
    }
    
    $file = $_FILES['imagem'];
    
    // Validar tipo de ficheiro
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de ficheiro não permitido. Use JPG, PNG, GIF ou WEBP'
        ]);
        exit;
    }
    
    // Validar tamanho (máx 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Imagem muito grande. Máximo 5MB'
        ]);
        exit;
    }
    
    // Gerar nome único para o ficheiro
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('cliente_', true) . '.' . $extension;
    
    // Caminho completo
    $uploadDir = '../../uploads/clientes/';
    $uploadPath = $uploadDir . $filename;
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log("Erro ao criar diretório: " . $uploadDir);
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao criar pasta de upload'
            ]);
            exit;
        }
    }
    
    // Verificar se diretório é gravável
    if (!is_writable($uploadDir)) {
        error_log("Diretório não é gravável: " . $uploadDir);
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Pasta de upload sem permissão de escrita'
        ]);
        exit;
    }
    
    // Mover ficheiro
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        error_log("Erro ao mover ficheiro de {$file['tmp_name']} para {$uploadPath}");
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao guardar imagem. Verifique permissões da pasta.'
        ]);
        exit;
    }
    
    error_log("Imagem guardada com sucesso: " . $uploadPath);
    
    // Retornar caminho relativo
    $relativePath = 'backoffice/uploads/clientes/' . $filename;
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'path' => $relativePath,
        'message' => 'Imagem enviada com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Upload Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar upload'
    ]);
}
?>
