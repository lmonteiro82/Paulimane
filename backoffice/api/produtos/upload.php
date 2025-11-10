<?php
/**
 * API - Upload de Imagem de Produto
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
    // Verificar se o arquivo foi enviado
    if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nenhuma imagem foi enviada'
        ]);
        exit;
    }
    
    $file = $_FILES['imagem'];
    
    // Validar tipo de arquivo
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WebP'
        ]);
        exit;
    }
    
    // Validar tamanho (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Arquivo muito grande. Tamanho máximo: 5MB'
        ]);
        exit;
    }
    
    // Criar diretório se não existir
    $uploadDir = '../../uploads/produtos/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Gerar nome único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('produto_') . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $relativePath = 'backoffice/uploads/produtos/' . $filename;
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Imagem enviada com sucesso',
            'path' => $relativePath
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao salvar imagem'
        ]);
    }

} catch (Exception $e) {
    error_log("Upload Produto Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao fazer upload da imagem'
    ]);
}
?>
