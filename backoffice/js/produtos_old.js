/**
 * Gestão de Produtos
 */

// Elementos
const produtosGrid = document.getElementById('produtosGrid');
const modal = document.getElementById('modal');
const produtoForm = document.getElementById('produtoForm');
const btnAdd = document.getElementById('btnAdd');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');
const produtoId = document.getElementById('produtoId');
const imagemFile = document.getElementById('imagemFile');
const imagePreview = document.getElementById('imagePreview');
const imagemPath = document.getElementById('imagemPath');
const nome = document.getElementById('nome');
const descricao = document.getElementById('descricao');
const categoriaId = document.getElementById('categoriaId');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');

let uploadedImagePath = '';

// Carregar produtos e categorias ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadCategorias();
    loadProdutos();
});

// Botão adicionar
btnAdd.addEventListener('click', () => {
    openModal();
});

// Botão cancelar
btnCancel.addEventListener('click', () => {
    closeModal();
});

// Preview de imagem
imagemFile.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    // Mostrar preview
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.src = e.target.result;
        imagePreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
    
    // Upload imagem
    await uploadImage(file);
});

// Submit formulário
produtoForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = produtoId.value;
    const nomeVal = nome.value.trim();
    const descricaoVal = descricao.value.trim();
    const imagemVal = imagemPath.value || uploadedImagePath;
    const categoriaIdVal = categoriaId.value;
    
    if (!nomeVal) {
        showError('Por favor, preencha o nome do produto');
        return;
    }
    
    if (!imagemVal) {
        showError('Por favor, faça upload da imagem');
        return;
    }
    
    if (!categoriaIdVal) {
        showError('Por favor, selecione uma categoria');
        return;
    }
    
    const data = {
        nome: nomeVal,
        descricao: descricaoVal,
        imagem: imagemVal,
        categoriaId: categoriaIdVal
    };
    
    if (id) {
        data.id = id;
        await updateProduto(data);
    } else {
        await createProduto(data);
    }
});

// Carregar categorias
async function loadCategorias() {
    try {
        const response = await fetch('api/catalogo/list.php');
        const result = await response.json();
        
        if (result.success) {
            renderCategorias(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar categorias:', error);
    }
}

// Renderizar categorias no select
function renderCategorias(categorias) {
    categoriaId.innerHTML = '<option value="">Selecione uma categoria</option>' +
        categorias.map(cat => `<option value="${cat.ID}">${cat.Nome}</option>`).join('');
}

// Carregar produtos
async function loadProdutos() {
    try {
        const response = await fetch('api/produtos/list.php');
        const result = await response.json();
        
        if (result.success) {
            renderProdutos(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar produtos:', error);
    }
}

// Renderizar produtos
function renderProdutos(produtos) {
    if (produtos.length === 0) {
        produtosGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Nenhum produto adicionado</p>';
        return;
    }
    
    produtosGrid.innerHTML = produtos.map(produto => `
        <div class="product-card">
            <img src="../${produto.Imagem}" alt="${produto.Nome}" class="product-card-image" onerror="this.src='https://via.placeholder.com/280x200'">
            <div class="product-card-content">
                <span class="product-category-badge">${produto.CategoriaNome || 'Sem categoria'}</span>
                <h3>${produto.Nome}</h3>
                <p>${produto.Descricao || 'Sem descrição'}</p>
            </div>
            <div class="product-card-actions">
                <button class="btn-icon btn-edit" onclick="editProduto(${produto.ID})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </button>
                <button class="btn-icon btn-delete" onclick="deleteProduto(${produto.ID}, '${produto.Nome.replace(/'/g, "\\'")}')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');
}

// Upload imagem
async function uploadImage(file) {
    try {
        const formData = new FormData();
        formData.append('imagem', file);
        
        const response = await fetch('api/produtos/upload.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            uploadedImagePath = result.path;
            imagemPath.value = result.path;
            showSuccess('Imagem enviada com sucesso');
        } else {
            showError(result.message || 'Erro ao enviar imagem');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao enviar imagem');
    }
}

// Criar produto
async function createProduto(data) {
    try {
        const response = await fetch('api/produtos/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Produto adicionado com sucesso');
            closeModal();
            loadProdutos();
        } else {
            showError(result.message || 'Erro ao adicionar produto');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao adicionar produto');
    }
}

// Atualizar produto
async function updateProduto(data) {
    try {
        const response = await fetch('api/produtos/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Produto atualizado com sucesso');
            closeModal();
            loadProdutos();
        } else {
            showError(result.message || 'Erro ao atualizar produto');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao atualizar produto');
    }
}

// Editar produto
async function editProduto(id) {
    try {
        const response = await fetch('api/produtos/list.php');
        const result = await response.json();
        
        if (result.success) {
            const produto = result.data.find(p => p.ID == id);
            if (produto) {
                produtoId.value = produto.ID;
                nome.value = produto.Nome;
                descricao.value = produto.Descricao || '';
                categoriaId.value = produto.CategoriaID;
                imagemPath.value = produto.Imagem;
                imagePreview.src = '../' + produto.Imagem;
                imagePreview.style.display = 'block';
                uploadedImagePath = produto.Imagem;
                
                modalTitle.textContent = 'Editar Produto';
                modal.classList.add('show');
            }
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

// Eliminar produto
async function deleteProduto(id, nomeProduto) {
    if (!confirm(`Tem certeza que deseja eliminar "${nomeProduto}"?`)) return;
    
    try {
        const response = await fetch('api/produtos/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Produto eliminado com sucesso');
            loadProdutos();
        } else {
            showError(result.message || 'Erro ao eliminar produto');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao eliminar produto');
    }
}

// Abrir modal
function openModal() {
    produtoId.value = '';
    nome.value = '';
    descricao.value = '';
    categoriaId.value = '';
    imagemPath.value = '';
    imagemFile.value = '';
    imagePreview.style.display = 'none';
    uploadedImagePath = '';
    modalTitle.textContent = 'Adicionar Produto';
    modal.classList.add('show');
}

// Fechar modal
function closeModal() {
    modal.classList.remove('show');
}

// Mostrar sucesso
function showSuccess(message) {
    successAlert.textContent = message;
    successAlert.classList.add('show');
    errorAlert.classList.remove('show');
    setTimeout(() => successAlert.classList.remove('show'), 5000);
}

// Mostrar erro
function showError(message) {
    errorAlert.textContent = message;
    errorAlert.classList.add('show');
    successAlert.classList.remove('show');
    setTimeout(() => errorAlert.classList.remove('show'), 5000);
}

// Expor funções globalmente
window.editProduto = editProduto;
window.deleteProduto = deleteProduto;
