/**
 * Gestão de Destaques
 */

// Elementos
const destaquesGrid = document.getElementById('destaquesGrid');
const modal = document.getElementById('modal');
const destaqueForm = document.getElementById('destaqueForm');
const btnAdd = document.getElementById('btnAdd');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');
const destaqueId = document.getElementById('destaqueId');
const imagemFile = document.getElementById('imagemFile');
const imagePreview = document.getElementById('imagePreview');
const imagemPath = document.getElementById('imagemPath');
const nome = document.getElementById('nome');
const descricao = document.getElementById('descricao');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');
const warningAlert = document.getElementById('warningAlert');

let uploadedImagePath = '';

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
destaqueForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = destaqueId.value;
    const nomeVal = nome.value.trim();
    const descricaoVal = descricao.value.trim();
    const imagemVal = imagemPath.value || uploadedImagePath;
    
    if (!nomeVal) {
        showError('Por favor, preencha o nome do destaque');
        return;
    }
    
    if (!imagemVal) {
        showError('Por favor, faça upload da imagem');
        return;
    }
    
    const data = {
        nome: nomeVal,
        descricao: descricaoVal,
        imagem: imagemVal
    };
    
    if (id) {
        data.id = id;
        await updateDestaque(data);
    } else {
        await createDestaque(data);
    }
});

// Carregar destaques
async function loadDestaques() {
    try {
        const response = await fetch('api/destaques/list.php');
        const result = await response.json();
        
        if (result.success) {
            renderDestaques(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar destaques:', error);
        showError('Erro ao carregar destaques');
    }
}

// Renderizar destaques
function renderDestaques(destaques) {
    if (destaques.length === 0) {
        destaquesGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #999;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 20px;">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                <p>Nenhum destaque adicionado</p>
                <p style="font-size: 14px; margin-top: 10px;">Adicione até 6 destaques para exibir na página inicial</p>
            </div>
        `;
        return;
    }
    
    destaquesGrid.innerHTML = destaques.map(destaque => `
        <div class="featured-card">
            <img src="../${destaque.Imagem}" alt="${destaque.Nome}" class="featured-card-image" onerror="this.src='https://via.placeholder.com/280x200'">
            <div class="featured-card-content">
                <h3>${destaque.Nome}</h3>
                <p>${destaque.Descricao || 'Sem descrição'}</p>
            </div>
            <div class="featured-card-actions">
                <button class="btn-icon btn-delete" onclick="removeDestaque(${destaque.ID}, '${destaque.Nome.replace(/'/g, "\\'")}')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Remover
                </button>
            </div>
        </div>
    `).join('');
    
    // Mostrar aviso se tiver 6 destaques
    if (destaques.length === 6) {
        showWarning('Limite de 6 destaques atingido');
    }
}

// Upload imagem
async function uploadImage(file) {
    try {
        const formData = new FormData();
        formData.append('imagem', file);
        
        const response = await fetch('api/destaques/upload.php', {
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

// Criar destaque
async function createDestaque(data) {
    try {
        const response = await fetch('api/destaques/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Destaque adicionado com sucesso');
            closeModal();
            loadDestaques();
        } else {
            showError(result.message || 'Erro ao adicionar destaque');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao adicionar destaque');
    }
}

// Atualizar destaque
async function updateDestaque(data) {
    try {
        const response = await fetch('api/destaques/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Destaque atualizado com sucesso');
            closeModal();
            loadDestaques();
        } else {
            showError(result.message || 'Erro ao atualizar destaque');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao atualizar destaque');
    }
}

// Remover destaque
async function removeDestaque(id, nomeDestaque) {
    if (!confirm(`Tem certeza que deseja remover "${nomeDestaque}" dos destaques?`)) return;
    
    try {
        const response = await fetch('api/destaques/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Destaque removido com sucesso');
            loadDestaques();
        } else {
            showError(result.message || 'Erro ao remover destaque');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao remover destaque');
    }
}

// Abrir modal
async function openModal() {
    // Verificar se já tem 6 destaques
    try {
        const response = await fetch('api/destaques/list.php');
        const result = await response.json();
        
        if (result.success && result.data.length >= 6) {
            showError('Limite de 6 destaques atingido. Remova um destaque antes de adicionar outro.');
            return;
        }
    } catch (error) {
        console.error('Erro:', error);
    }
    
    destaqueId.value = '';
    nome.value = '';
    descricao.value = '';
    imagemPath.value = '';
    imagemFile.value = '';
    imagePreview.style.display = 'none';
    uploadedImagePath = '';
    modalTitle.textContent = 'Adicionar Destaque';
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
window.removeDestaque = removeDestaque;
