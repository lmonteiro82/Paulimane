<?php
/**
 * Proteção de Acesso - Nível 2 Necessário
 */
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Verificar nível de acesso
$nivel_usuario = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 1;

// Nível 3 tem acesso total, nível 2 ou superior pode acessar categorias
if ($nivel_usuario < 2) {
    header('Location: acesso-negado.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Categorias | Paulimane Backoffice</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/logooriginal.png?v=2" type="image/png">
    <style>
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .catalog-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .catalog-card:hover {
            transform: translateY(-5px);
        }
        .catalog-card-image {
            width: 100%;
            height: 200px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 15px;
            background: #f5f5f5;
        }
        .catalog-card-content {
            flex: 1;
        }
        .catalog-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .catalog-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .catalog-card-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: auto;
        }
        .btn-icon {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-edit {
            background: #007bff;
            color: white;
        }
        .btn-edit:hover {
            background: #0056b3;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #F26522;
        }
        .image-preview {
            width: 100%;
            max-height: 200px;
            border-radius: 10px;
            object-fit: cover;
            margin: 15px 0;
            display: block;
        }
        .btn-primary {
            background: linear-gradient(135deg, #F26522 0%, #D95518 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
        }
        .alert.show {
            display: block;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* PDF Method Toggle */
        .pdf-method-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 10px;
        }
        .method-option {
            flex: 1;
            cursor: pointer;
            margin: 0;
        }
        .method-option input[type="radio"] {
            display: none;
        }
        .method-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            background: transparent;
            color: #6c757d;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .method-option input[type="radio"]:checked + .method-label {
            background: white;
            color: #F26522;
            box-shadow: 0 2px 8px rgba(242, 101, 34, 0.15);
        }
        .method-label svg {
            flex-shrink: 0;
        }
        
        /* PDF Section */
        .pdf-section {
            margin-top: 15px;
        }
        
        /* Help Text */
        .help-text {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6c757d;
            margin-top: 8px;
        }
        .help-text code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
        }
        
        /* PDF Browser */
        .pdf-browser {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            min-height: 200px;
            max-height: 300px;
            overflow-y: auto;
        }
        .pdf-browser-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 15px;
            color: #6c757d;
            padding: 40px 20px;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #F26522;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* PDF File Card */
        .pdf-file-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .pdf-file-card:hover {
            border-color: #F26522;
            background: #fff5f0;
            transform: translateX(5px);
        }
        .pdf-file-card.selected {
            border-color: #F26522;
            background: #fff5f0;
            box-shadow: 0 2px 8px rgba(242, 101, 34, 0.15);
        }
        .pdf-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #F26522 0%, #D95518 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .pdf-icon svg {
            color: white;
        }
        .pdf-info {
            flex: 1;
            min-width: 0;
        }
        .pdf-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pdf-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #6c757d;
        }
        .pdf-empty {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        .pdf-empty svg {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
            opacity: 0.3;
        }
        
        /* Selected File */
        .selected-file {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            background: #d4edda;
            color: #155724;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 10px;
        }
        .selected-file:empty {
            display: none;
        }
        .selected-file svg {
            flex-shrink: 0;
        }
        
        /* Browse Button */
        .btn-browse {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 20px;
            background: linear-gradient(135deg, #F26522 0%, #D95518 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-browse:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(242, 101, 34, 0.3);
        }
        .btn-browse svg {
            flex-shrink: 0;
        }
        
        /* FTP Instructions */
        .ftp-instructions {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .instruction-step {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .instruction-step:last-child {
            margin-bottom: 0;
        }
        .step-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #F26522 0%, #D95518 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }
        .step-content {
            flex: 1;
        }
        .step-content strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .step-content p {
            color: #6c757d;
            font-size: 13px;
            margin: 5px 0;
            line-height: 1.5;
        }
        .step-content code {
            display: block;
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #F26522;
            margin-top: 8px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-bar">
            <button class="menu-toggle" id="menuToggle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            
            <div class="top-bar-right">
                <div class="user-info">
                    <span class="user-name" id="userName">Administrador</span>
                    <div class="user-avatar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="alert alert-success" id="successAlert"></div>
            <div class="alert alert-error" id="errorAlert"></div>

            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h1>Gestão de Categorias</h1>
                    <p>Adicionar e gerir categorias de produtos</p>
                </div>
                <button class="btn-primary" id="btnAdd" style="width: auto;">
                    + Adicionar Card
                </button>
            </div>

            <div class="catalog-grid" id="catalogoGrid">
                <!-- Cards serão inseridos aqui -->
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Adicionar Card</h2>
            <form id="catalogoForm">
                <input type="hidden" id="catalogoId">
                
                <div class="form-group">
                    <label>Imagem *</label>
                    <input type="file" id="imagemFile" accept="image/*">
                    <img id="imagePreview" class="image-preview" style="display:none;">
                    <input type="hidden" id="imagemPath">
                </div>

                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" id="nome" required>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" placeholder="Descrição opcional do card"></textarea>
                </div>

                <div class="form-group">
                    <label>PDF do Catálogo *</label>
                    
                    <button type="button" id="btnUploadLargePdf" class="btn-browse">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Escolher PDF
                    </button>
                    <input type="file" id="pdfFileLarge" accept="application/pdf" style="display: none;">
                    
                    <div id="uploadProgress" style="display: none; margin-top: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; color: #666;">
                            <span id="uploadProgressText">Enviando...</span>
                            <span id="uploadProgressPercent">0%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                            <div id="uploadProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #F26522 0%, #D95518 100%); transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                    
                    <p class="help-text" style="margin-top: 10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Sem limite de tamanho
                    </p>
                    
                    <input type="hidden" id="pdfPath">
                    <p id="pdfFileName" class="selected-file"></p>
                </div>

                <button type="submit" class="btn-primary">Guardar</button>
                <button type="button" class="btn-secondary" id="btnCancel">Cancelar</button>
            </form>
        </div>
    </div>

    <script src="js/dashboard.js"></script>
    <script src="js/categorias.js"></script>
</body>
</html>
