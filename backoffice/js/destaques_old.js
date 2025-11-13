/**
 * Gestão de Produtos em Destaque
 */

// Elementos
const destaquesGrid = document.getElementById('destaquesGrid');
const modal = document.getElementById('modal');
const btnAdd = document.getElementById('btnAdd');
const btnCancel = document.getElementById('btnCancel');
const produtosDisponiveis = document.getElementById('produtosDisponiveis');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');
const warningAlert = document.getElementById('warningAlert');

// Carregar destaques ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadDestaques();
});

// Botão adicionar
btnAdd.addEventListener('click', () => {
    openModal();
});

// Botão cancelar
btnCancel.addEventListener('click', () => {
    closeModal();
});

// Carregar produtos em destaque
async function loadDestaques() {
    try {
        const response = await fetch('api/destaques/list.php');
        const result = await response.json();
        
        if (result.success) {
            renderDestaques(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar destaques:', error);
        showError('Erro ao carregar produtos em destaque');
    }
}

// Renderizar produtos em destaque
function renderDestaques(destaques) {
    if (destaques.length === 0) {
        destaquesGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1/-1;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                <p>Nenhum produto em destaque</p>
                <p style="font-size: 14px; margin-top: 10px;">Adicione até 6 produtos para exibir na página inicial</p>
            </div>
        `;
        return;
    }
    
    destaquesGrid.innerHTML = destaques.map(destaque => `
        <div class="featured-card">
            <img src="../${destaque.Imagem}" alt="${destaque.Nome}" class="featured-card-image" onerror="this.src='https://via.placeholder.com/280x200'">
            <div class="featured-card-content">
                <span class="featured-category-badge">${destaque.CategoriaNome || 'Sem categoria'}</span>
                <h3>${destaque.Nome}</h3>
                <p>${destaque.Descricao || 'Sem descrição'}</p>
            </div>
            <div class="featured-card-actions">
                <button class="btn-icon btn-delete" onclick="removeDestaque(${destaque.DestaqueID}, '${destaque.Nome.replace(/'/g, "\\'")}')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Remover
                </button>
            </div>
        </div>
    `).join('');
    
    // Mostrar aviso se tiver 6 produtos
    if (destaques.length === 6) {
        showWarning('Limite de 6 produtos em destaque atingido');
    }
}

// Abrir modal e carregar produtos disponíveis
async function openModal() {
    try {
        // Verificar se já tem 6 produtos
        const responseDestaques = await fetch('api/destaques/list.php');
        const resultDestaques = await responseDestaques.json();
        
        if (resultDestaques.success && resultDestaques.data.length >= 6) {
            showError('Limite de 6 produtos atingido. Remova um produto antes de adicionar outro.');
            return;
        }
        
        // Carregar produtos disponíveis
        const response = await fetch('api/destaques/produtos-disponiveis.php');
        const result = await response.json();
        
        if (result.success) {
            if (result.data.length === 0) {
                produtosDisponiveis.innerHTML = '<p style="text-align: center; color: #999; padding: 40px;">Todos os produtos já estão em destaque ou não há produtos cadastrados.</p>';
            } else {
                produtosDisponiveis.innerHTML = result.data.map(produto => `
                    <div class="product-select-card" onclick="addDestaque(${produto.ID})">
                        <img src="../${produto.Imagem}" alt="${produto.Nome}" onerror="this.src='https://via.placeholder.com/250x150'">
                        <h4>${produto.Nome}</h4>
                        <p>${produto.CategoriaNome || 'Sem categoria'}</p>
                    </div>
                `).join('');
            }
            modal.classList.add('show');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao carregar produtos disponíveis');
    }
}

// Fechar modal
function closeModal() {
    modal.classList.remove('show');
}

// Adicionar produto aos destaques
async function addDestaque(produtoId) {
    try {
        const response = await fetch('api/destaques/add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ produtoId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Produto adicionado aos destaques com sucesso');
            closeModal();
            loadDestaques();
        } else {
            showError(result.message || 'Erro ao adicionar produto aos destaques');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao adicionar produto aos destaques');
    }
}

// Remover produto dos destaques
async function removeDestaque(id, nomeProduto) {
    if (!confirm(`Tem certeza que deseja remover "${nomeProduto}" dos destaques?`)) return;
    
    try {
        const response = await fetch('api/destaques/remove.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Produto removido dos destaques com sucesso');
            loadDestaques();
        } else {
            showError(result.message || 'Erro ao remover produto dos destaques');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao remover produto dos destaques');
    }
}

// Mostrar sucesso
function showSuccess(message) {
    successAlert.textContent = message;
    successAlert.classList.add('show');
    errorAlert.classList.remove('show');
    warningAlert.classList.remove('show');
    setTimeout(() => successAlert.classList.remove('show'), 5000);
}

// Mostrar erro
function showError(message) {
    errorAlert.textContent = message;
    errorAlert.classList.add('show');
    successAlert.classList.remove('show');
    warningAlert.classList.remove('show');
    setTimeout(() => errorAlert.classList.remove('show'), 5000);
}

// Mostrar aviso
function showWarning(message) {
    warningAlert.textContent = message;
    warningAlert.classList.add('show');
    successAlert.classList.remove('show');
    errorAlert.classList.remove('show');
    setTimeout(() => warningAlert.classList.remove('show'), 5000);
}

// Expor funções globalmente
window.addDestaque = addDestaque;
window.removeDestaque = removeDestaque;
