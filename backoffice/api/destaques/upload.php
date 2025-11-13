<?php
/**
 * API - Upload de Imagem para Destaque
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
            'message' => 'Nenhuma imagem foi enviada ou ocorreu um erro no upload'
        ]);
        exit;
    }
    
    $file = $_FILES['imagem'];
    
    // Validar tipo de arquivo
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Apenas imagens são permitidas (JPEG, PNG, GIF, WebP)'
        ]);
        exit;
    }
    
    // Validar tamanho (máximo 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'A imagem não pode exceder 5MB'
        ]);
        exit;
    }
    
    // Gerar nome único para o arquivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'destaque_' . uniqid() . '.' . $extension;
    
    // Diretório de upload
    $uploadDir = '../../uploads/destaques/';
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $uploadPath = $uploadDir . $fileName;
    
    // Mover arquivo para o diretório de upload
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao salvar a imagem'
        ]);
        exit;
    }
    
    // Retornar caminho relativo do arquivo
    $relativePath = 'backoffice/uploads/destaques/' . $fileName;
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Imagem enviada com sucesso',
        'path' => $relativePath,
        'filename' => $fileName
    ]);

} catch (Exception $e) {
    error_log("Upload Destaque Image Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao fazer upload da imagem'
    ]);
}
?>
