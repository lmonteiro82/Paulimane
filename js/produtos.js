/**
 * Produtos por Categoria - Frontend
 * Mostra categorias primeiro, depois produtos ao clicar
 */

let productCards = [];
let allProducts = [];
let categorias = [];
let categoriaAtual = null;

const searchInput = document.getElementById('searchInput');
const productsGrid = document.getElementById('productsGrid');
const categoriesGrid = document.getElementById('categoriesGrid');
const categoryTitle = document.getElementById('categoryTitle');
const categorySubtitle = document.getElementById('categorySubtitle');
const btnVoltar = document.getElementById('btnVoltar');
const categoriesSection = document.getElementById('categoriesSection');
const productsSection = document.getElementById('productsSection');
const searchSection = document.getElementById('searchSection');

// Carregar categorias ao iniciar
document.addEventListener('DOMContentLoaded', carregarCategorias);

// Carregar categorias
async function carregarCategorias() {
    try {
        const response = await fetch('api/catalogo.php');
        const result = await response.json();
        
        if (result.success && result.data.length > 0) {
            categorias = result.data;
            renderizarCategorias(categorias);
        } else {
            categoriesGrid.innerHTML = '<p style="text-align: center; color: #666; padding: 40px;">Nenhuma categoria disponível.</p>';
        }
    } catch (error) {
        console.error('Erro ao carregar categorias:', error);
        categoriesGrid.innerHTML = '<p style="text-align: center; color: #dc3545; padding: 40px;">Erro ao carregar categorias.</p>';
    }
}

// Renderizar categorias
function renderizarCategorias(cats) {
    categoriesGrid.innerHTML = cats.map(cat => `
        <div class="product-card" onclick="verProdutosCategoria(${cat.ID}, '${cat.Nome}')" style="cursor: pointer;">
            <div class="product-image">
                <img src="${cat.Imagem}" alt="${cat.Nome}" onerror="this.src='https://via.placeholder.com/400x300?text=Sem+Imagem'">
            </div>
            <div class="product-info">
                <h3 class="product-name">${cat.Nome}</h3>
                <p class="product-description">${cat.Descricao || 'Clique para ver produtos'}</p>
            </div>
        </div>
    `).join('');
}

// Ver produtos de uma categoria
async function verProdutosCategoria(categoriaId, categoriaNome) {
    categoriaAtual = { id: categoriaId, nome: categoriaNome };
    
    // Atualizar título
    categoryTitle.textContent = categoriaNome;
    categorySubtitle.textContent = 'Produtos desta categoria';
    
    // Mostrar seção de produtos e esconder categorias
    categoriesSection.style.display = 'none';
    productsSection.style.display = 'block';
    searchSection.style.display = 'block';
    btnVoltar.style.display = 'inline-block';
    
    // Carregar produtos
    await carregarProdutos(categoriaId);
}

// Carregar produtos de uma categoria
async function carregarProdutos(categoriaId) {
    try {
        productsGrid.innerHTML = '<div style="text-align: center; padding: 60px 20px; color: #999;"><p>A carregar produtos...</p></div>';
        
        const response = await fetch(`api/produtos.php?categoria=${categoriaId}`);
        const result = await response.json();
        
        if (result.success && result.data.length > 0) {
            allProducts = result.data;
            renderizarProdutos(allProducts);
        } else {
            productsGrid.innerHTML = '<p style="text-align: center; color: #666; padding: 40px;">Nenhum produto disponível nesta categoria.</p>';
        }
    } catch (error) {
        console.error('Erro ao carregar produtos:', error);
        productsGrid.innerHTML = '<p style="text-align: center; color: #dc3545; padding: 40px;">Erro ao carregar produtos.</p>';
    }
}

// Renderizar produtos
function renderizarProdutos(products) {
    productsGrid.innerHTML = products.map(product => `
        <div class="product-card" data-category="all">
            <div class="product-image">
                <img src="${product.Imagem}" alt="${product.Nome}" onerror="this.src='https://via.placeholder.com/400x300?text=Sem+Imagem'">
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.Nome}</h3>
                <p class="product-description">${product.Descricao || 'Produto de qualidade'}</p>
            </div>
        </div>
    `).join('');
    
    // Atualizar referência aos cards
    productCards = document.querySelectorAll('.product-card');
    
    // Adicionar animações aos novos cards
    addProductAnimations();
}

// Voltar às categorias
btnVoltar.addEventListener('click', () => {
    categoriaAtual = null;
    categoryTitle.textContent = 'Produtos';
    categorySubtitle.textContent = 'Selecione uma categoria para ver os produtos';
    
    categoriesSection.style.display = 'block';
    productsSection.style.display = 'none';
    searchSection.style.display = 'none';
    btnVoltar.style.display = 'none';
    
    searchInput.value = '';
});

// Expor função globalmente
window.verProdutosCategoria = verProdutosCategoria;

// Pesquisa de produtos
searchInput.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    
    if (!searchTerm) {
        renderizarProdutos(allProducts);
        return;
    }
    
    const filtrados = allProducts.filter(p => 
        p.Nome.toLowerCase().includes(searchTerm) || 
        (p.Descricao && p.Descricao.toLowerCase().includes(searchTerm))
    );
    
    renderizarProdutos(filtrados);
});

// Adicionar animações aos produtos
function addProductAnimations() {
    // Add transition to product cards
    productCards.forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });

    // Add entrance animation to products
    const productObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 50);
            }
        });
    }, {
        threshold: 0.1
    });

    productCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        productObserver.observe(card);
    });
}
