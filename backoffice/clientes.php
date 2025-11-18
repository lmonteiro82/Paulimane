<?php
/**
 * Proteção de Acesso - Nível 1 ou Superior
 */
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Todos os níveis autenticados podem acessar clientes
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Clientes | Paulimane Backoffice</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/logooriginal.png?v=2" type="image/png">
    <style>
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .team-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .team-card:hover {
            transform: translateY(-5px);
        }
        .team-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .team-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }
        .team-card p {
            color: #F26522;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .team-card-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
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
        .btn-delete {
            background: #dc3545;
            color: white;
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
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #F26522;
        }
        .image-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 15px auto;
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
                    <h1>Gestão de Clientes</h1>
                    <p>Adicionar e gerir logos de clientes</p>
                </div>
                <button class="btn-primary" id="btnAdd" style="width: auto;">
                    + Adicionar Cliente
                </button>
            </div>

            <div class="team-grid" id="clientesGrid">
                <!-- Cards serão inseridos aqui -->
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Adicionar Cliente</h2>
            <form id="clienteForm">
                <input type="hidden" id="clienteId">
                
                <div class="form-group">
                    <label>Logo *</label>
                    <input type="file" id="imagemFile" accept="image/*">
                    <img id="imagePreview" class="image-preview" style="display:none;">
                    <input type="hidden" id="imagemPath">
                </div>

                <div class="form-group">
                    <label for="name">Nome do Cliente *</label>
                    <input type="text" id="name" required>
                </div>

                <button type="submit" class="btn-primary">Guardar</button>
                <button type="button" class="btn-secondary" id="btnCancel">Cancelar</button>
            </form>
        </div>
    </div>

    <script src="js/dashboard.js"></script>
    <script src="js/clientes.js"></script>
</body>
</html>
