/**
 * Gestão de Produtos - JavaScript
 */

let produtos = [];
let categorias = [];
let editandoId = null;

// Elementos
const modal = document.getElementById('modalProduto');
const form = document.getElementById('produtoForm');
const btnNovo = document.getElementById('btnNovo');
const btnCancelar = document.getElementById('btnCancelar');
const produtosGrid = document.getElementById('produtosGrid');
const imageInput = document.getElementById('imagem');
const imagePreview = document.getElementById('imagePreview');
const alertSuccess = document.getElementById('alertSuccess');
const alertError = document.getElementById('alertError');

// Carregar categorias e produtos ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    carregarCategorias();
    carregarProdutos();
});

// Carregar categorias para o select
async function carregarCategorias() {
    try {
        const response = await fetch('api/categorias.php');
        const result = await response.json();
        
        if (result.success) {
            categorias = result.data;
            const select = document.getElementById('categoria');
            select.innerHTML = '<option value="">Selecione uma categoria</option>';
            
            categorias.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.ID;
                option.textContent = cat.Nome;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Erro ao carregar categorias:', error);
    }
}

// Carregar produtos
async function carregarProdutos() {
    try {
        const response = await fetch('api/produtos.php');
        const result = await response.json();
        
        if (result.success) {
            produtos = result.data;
            renderizarProdutos(produtos);
        } else {
            produtosGrid.innerHTML = '<div style="text-align: center; padding: 60px 20px; color: #999; grid-column: 1 / -1;"><p>Nenhum produto encontrado</p></div>';
        }
    } catch (error) {
        console.error('Erro ao carregar produtos:', error);
        produtosGrid.innerHTML = '<div style="text-align: center; padding: 60px 20px; color: #dc3545; grid-column: 1 / -1;"><p>Erro ao carregar produtos</p></div>';
    }
}

// Renderizar produtos em cards
function renderizarProdutos(lista) {
    if (lista.length === 0) {
        produtosGrid.innerHTML = '<div style="text-align: center; padding: 60px 20px; color: #999; grid-column: 1 / -1;"><p>Nenhum produto encontrado</p></div>';
        return;
    }

    produtosGrid.innerHTML = lista.map(produto => {
        const categoriaNome = categorias.find(c => c.ID == produto.CategoriaID)?.Nome || 'Sem categoria';
        const imagemPath = produto.Imagem && produto.Imagem[0] !== '/' ? '/' + produto.Imagem : produto.Imagem;
        
        return `
            <div class="catalog-card">
                <img src="${imagemPath || 'https://via.placeholder.com/400x300?text=Sem+Imagem'}" 
                     alt="${produto.Nome}"
                     class="catalog-card-image"
                     onerror="this.src='https://via.placeholder.com/400x300?text=Sem+Imagem'">
                <div class="catalog-card-content">
                    <h3>${produto.Nome}</h3>
                    <p>${produto.Descricao || 'Sem descrição'}</p>
                    <span class="category-badge">${categoriaNome}</span>
                </div>
                <div class="catalog-card-actions">
                    <button class="btn-icon btn-edit" onclick="editarProduto(${produto.ID})" title="Editar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                    <button class="btn-icon btn-delete" onclick="apagarProduto(${produto.ID})" title="Apagar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    }).join('');
}


// Abrir modal para novo produto
btnNovo.addEventListener('click', () => {
    editandoId = null;
    form.reset();
    imagePreview.style.display = 'none';
    document.getElementById('modalTitle').textContent = 'Novo Produto';
    modal.classList.add('show');
});

// Fechar modal
btnCancelar.addEventListener('click', fecharModal);

// Fechar modal ao clicar fora
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        fecharModal();
    }
});

function fecharModal() {
    modal.classList.remove('show');
    form.reset();
    imagePreview.style.display = 'none';
    editandoId = null;
}

// Preview da imagem
imageInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none';
    }
});

// Submeter formulário
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    const url = editandoId ? `api/produtos.php?id=${editandoId}` : 'api/produtos.php';
    const method = editandoId ? 'PUT' : 'POST';
    
    try {
        let response;
        if (method === 'PUT') {
            // Para PUT, enviar como JSON (sem imagem por enquanto)
            const data = {
                nome: formData.get('nome'),
                descricao: formData.get('descricao'),
                categoria: formData.get('categoria')
            };
            response = await fetch(url, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
        } else {
            // Para POST, enviar FormData com imagem
            response = await fetch(url, {
                method: 'POST',
                body: formData
            });
        }
        
        const result = await response.json();
        
        if (result.success) {
            mostrarAlerta(editandoId ? 'Produto atualizado com sucesso!' : 'Produto criado com sucesso!', 'success');
            fecharModal();
            carregarProdutos();
        } else {
            mostrarAlerta('Erro: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Erro ao guardar produto:', error);
        mostrarAlerta('Erro ao guardar produto', 'error');
    }
});

// Mostrar alertas
function mostrarAlerta(mensagem, tipo) {
    const alert = tipo === 'success' ? alertSuccess : alertError;
    alert.textContent = mensagem;
    alert.classList.add('show');
    
    setTimeout(() => {
        alert.classList.remove('show');
    }, 5000);
}

// Editar produto
function editarProduto(id) {
    const produto = produtos.find(p => p.ID === id);
    if (!produto) return;
    
    editandoId = id;
    document.getElementById('produtoId').value = id;
    document.getElementById('nome').value = produto.Nome;
    document.getElementById('descricao').value = produto.Descricao || '';
    document.getElementById('categoria').value = produto.CategoriaID;
    
    if (produto.Imagem) {
        const imagemPath = produto.Imagem[0] !== '/' ? '/' + produto.Imagem : produto.Imagem;
        imagePreview.src = imagemPath;
        imagePreview.style.display = 'block';
    }
    
    document.getElementById('modalTitle').textContent = 'Editar Produto';
    modal.classList.add('show');
}

// Apagar produto
async function apagarProduto(id) {
    if (!confirm('Tem a certeza que deseja apagar este produto?')) return;
    
    try {
        const response = await fetch(`api/produtos.php?id=${id}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            mostrarAlerta('Produto apagado com sucesso!', 'success');
            carregarProdutos();
        } else {
            mostrarAlerta('Erro: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Erro ao apagar produto:', error);
        mostrarAlerta('Erro ao apagar produto', 'error');
    }
}

// Expor funções globalmente
window.editarProduto = editarProduto;
window.apagarProduto = apagarProduto;
