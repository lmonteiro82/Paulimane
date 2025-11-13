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

// Nível 3 tem acesso total, nível 2 ou superior pode acessar destaques
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
    <title>Produtos em Destaque | Paulimane Backoffice</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/logo.png?v=2" type="image/png">
    <style>
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .featured-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .featured-card:hover {
            transform: translateY(-5px);
        }
        .featured-card-image {
            width: 100%;
            height: 200px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 15px;
            background: #f5f5f5;
        }
        .featured-card-content {
            flex: 1;
        }
        .featured-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .featured-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .featured-category-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #F26522;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .featured-card-actions {
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
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .product-select-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .product-select-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .product-select-card:hover {
            border-color: #F26522;
            background: #fff;
        }
        .product-select-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .product-select-card h4 {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }
        .product-select-card p {
            font-size: 12px;
            color: #666;
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
        .alert-warning {
            background: #fff3cd;
            color: #856404;
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
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state svg {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #F26522;
        }
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        .image-preview {
            width: 100%;
            max-height: 200px;
            border-radius: 10px;
            object-fit: cover;
            margin-top: 15px;
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
            <div class="alert alert-warning" id="warningAlert"></div>

            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h1>Destaques</h1>
                    <p>Gerir os destaques exibidos na página inicial (máximo 6)</p>
                </div>
                <button class="btn-primary" id="btnAdd" style="width: auto;">
                    + Adicionar Destaque
                </button>
            </div>

            <div class="featured-grid" id="destaquesGrid">
                <!-- Produtos em destaque serão inseridos aqui -->
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Adicionar Destaque</h2>
            <form id="destaqueForm">
                <input type="hidden" id="destaqueId">
                
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
                    <textarea id="descricao" placeholder="Descrição opcional do destaque"></textarea>
                </div>

                <button type="submit" class="btn-primary">Guardar</button>
                <button type="button" class="btn-secondary" id="btnCancel">Cancelar</button>
            </form>
        </div>
    </div>

    <script src="js/dashboard.js"></script>
    <script src="js/destaques.js"></script>
</body>
</html>
