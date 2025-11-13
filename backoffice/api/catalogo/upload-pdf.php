<?php
/**
 * API - Upload de PDF para Categoria
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
    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nenhum arquivo PDF foi enviado ou ocorreu um erro no upload'
        ]);
        exit;
    }
    
    $file = $_FILES['pdf'];
    
    // Validar tipo de arquivo
    $allowedTypes = ['application/pdf'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Apenas arquivos PDF são permitidos'
        ]);
        exit;
    }
    
    // Validar tamanho (máximo 10MB)
    $maxSize = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $maxSize) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'O arquivo PDF não pode exceder 10MB'
        ]);
        exit;
    }
    
    // Gerar nome único para o arquivo
    $extension = 'pdf';
    $fileName = 'catalogo_' . uniqid() . '.' . $extension;
    
    // Diretório de upload
    $uploadDir = '../../uploads/catalogo/pdfs/';
    
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
            'message' => 'Erro ao salvar o arquivo PDF'
        ]);
        exit;
    }
    
    // Retornar caminho relativo do arquivo
    $relativePath = 'backoffice/uploads/catalogo/pdfs/' . $fileName;
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'PDF enviado com sucesso',
        'path' => $relativePath,
        'filename' => $fileName
    ]);

} catch (Exception $e) {
    error_log("Upload PDF Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao fazer upload do PDF'
    ]);
}
?>
