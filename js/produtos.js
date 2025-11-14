/**
 * Produtos por Categoria - Frontend
 */

let productCards = document.querySelectorAll('.product-card');
const searchInput = document.getElementById('searchInput');
const productsGrid = document.getElementById('productsGrid');
const categoryTitle = document.getElementById('categoryTitle');
const categorySubtitle = document.getElementById('categorySubtitle');

// Obter ID da categoria da URL
const urlParams = new URLSearchParams(window.location.search);
const categoriaId = urlParams.get('categoria');
const categoriaNome = urlParams.get('nome');

// Atualizar título se tiver nome da categoria
if (categoriaNome) {
    categoryTitle.textContent = categoriaNome;
    categorySubtitle.textContent = `Produtos da categoria ${categoriaNome}`;
}

// Carregar produtos da base de dados
async function loadProducts() {
    try {
        const url = categoriaId 
            ? `api/produtos.php?categoria=${categoriaId}` 
            : 'api/produtos.php';
            
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data.length > 0) {
            renderProducts(result.data);
        } else {
            productsGrid.innerHTML = '<p style="text-align: center; color: #666; padding: 40px;">Nenhum produto disponível nesta categoria.</p>';
        }
    } catch (error) {
        console.error('Erro ao carregar produtos:', error);
        productsGrid.innerHTML = '<p style="text-align: center; color: #dc3545; padding: 40px;">Erro ao carregar produtos. Por favor, tente novamente.</p>';
    }
}

// Renderizar produtos
function renderProducts(products) {
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
    
    // Reativar pesquisa
    setupSearch();
}

// Carregar produtos ao iniciar
document.addEventListener('DOMContentLoaded', loadProducts);

// Configurar pesquisa
function setupSearch() {
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();

            // Se o campo estiver vazio, mostrar todos os produtos
            if (!searchTerm) {
                productCards.forEach(card => {
                    card.style.display = 'block';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                });
                return;
            }

            productCards.forEach(card => {
                const productName = card.querySelector('.product-name').textContent.toLowerCase();

                if (productName.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    }
}

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
