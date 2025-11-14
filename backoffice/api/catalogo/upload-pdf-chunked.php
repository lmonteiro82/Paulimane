<?php
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

// Diretório de upload (pasta FTP)
$uploadDir = '../../uploads/catalogo/pdfs-ftp/';

// Criar diretório se não existir
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Verificar se é uma requisição de chunk
if (!isset($_POST['chunk']) || !isset($_POST['chunks']) || !isset($_POST['filename'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Parâmetros inválidos'
    ]);
    exit;
}

$chunk = intval($_POST['chunk']);
$chunks = intval($_POST['chunks']);
$filename = basename($_POST['filename']); // Sanitizar nome do arquivo
$tempDir = $uploadDir . 'temp/';

// Criar diretório temporário
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0755, true);
}

// Verificar se há arquivo no upload
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Erro no upload do chunk'
    ]);
    exit;
}

// Salvar chunk temporário
$chunkFile = $tempDir . $filename . '.part' . $chunk;
if (!move_uploaded_file($_FILES['file']['tmp_name'], $chunkFile)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao salvar chunk'
    ]);
    exit;
}

// Verificar se todos os chunks foram recebidos
$uploadedChunks = 0;
for ($i = 0; $i < $chunks; $i++) {
    if (file_exists($tempDir . $filename . '.part' . $i)) {
        $uploadedChunks++;
    }
}

// Se todos os chunks foram recebidos, juntar o arquivo
if ($uploadedChunks === $chunks) {
    // Verificar se arquivo já existe e gerar nome único se necessário
    $finalFilename = $filename;
    $counter = 1;
    $pathInfo = pathinfo($filename);
    $baseName = $pathInfo['filename'];
    $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
    
    while (file_exists($uploadDir . $finalFilename)) {
        $finalFilename = $baseName . '_' . $counter . '.' . $extension;
        $counter++;
    }
    
    $finalPath = $uploadDir . $finalFilename;
    
    // Abrir arquivo final para escrita
    $finalFile = fopen($finalPath, 'wb');
    
    if (!$finalFile) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao criar arquivo final'
        ]);
        exit;
    }
    
    // Juntar todos os chunks
    for ($i = 0; $i < $chunks; $i++) {
        $chunkPath = $tempDir . $filename . '.part' . $i;
        $chunkData = file_get_contents($chunkPath);
        fwrite($finalFile, $chunkData);
        unlink($chunkPath); // Deletar chunk temporário
    }
    
    fclose($finalFile);
    
    // Limpar diretório temporário se estiver vazio
    $tempFiles = scandir($tempDir);
    if (count($tempFiles) <= 2) { // Apenas . e ..
        rmdir($tempDir);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'PDF enviado com sucesso',
        'path' => '/backoffice/uploads/catalogo/pdfs-ftp/' . $finalFilename,
        'filename' => $finalFilename,
        'size' => filesize($finalPath),
        'size_mb' => round(filesize($finalPath) / 1024 / 1024, 2)
    ]);
} else {
    // Chunk recebido com sucesso, aguardando próximos
    echo json_encode([
        'success' => true,
        'message' => 'Chunk recebido',
        'chunk' => $chunk,
        'chunks' => $chunks,
        'progress' => round(($uploadedChunks / $chunks) * 100, 2)
    ]);
}
