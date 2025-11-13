/**
 * Gestão de Catálogo
 */

// Elementos
const catalogoGrid = document.getElementById('catalogoGrid');
const modal = document.getElementById('modal');
const catalogoForm = document.getElementById('catalogoForm');
const btnAdd = document.getElementById('btnAdd');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');
const catalogoId = document.getElementById('catalogoId');
const imagemFile = document.getElementById('imagemFile');
const imagePreview = document.getElementById('imagePreview');
const imagemPath = document.getElementById('imagemPath');
const pdfFile = document.getElementById('pdfFile');
const pdfPath = document.getElementById('pdfPath');
const pdfFileName = document.getElementById('pdfFileName');
const nome = document.getElementById('nome');
const descricao = document.getElementById('descricao');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');

let uploadedImagePath = '';
let uploadedPdfPath = '';

// Carregar catálogo ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadCatalogo();
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

// Upload de PDF
pdfFile.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    // Mostrar nome do arquivo
    pdfFileName.textContent = `Arquivo selecionado: ${file.name}`;
    
    // Upload PDF
    await uploadPdf(file);
});

// Submit formulário
catalogoForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = catalogoId.value;
    const nomeVal = nome.value.trim();
    const descricaoVal = descricao.value.trim();
    const imagemVal = imagemPath.value || uploadedImagePath;
    const pdfVal = pdfPath.value || uploadedPdfPath;
    
    if (!nomeVal) {
        showError('Por favor, preencha o nome do card');
        return;
    }
    
    if (!imagemVal) {
        showError('Por favor, faça upload da imagem');
        return;
    }
    
    if (!pdfVal) {
        showError('Por favor, faça upload do PDF');
        return;
    }
    
    const data = {
        nome: nomeVal,
        descricao: descricaoVal,
        imagem: imagemVal,
        pdf: pdfVal
    };
    
    if (id) {
        data.id = id;
        await updateCatalogo(data);
    } else {
        await createCatalogo(data);
    }
});

// Carregar catálogo
async function loadCatalogo() {
    try {
        const response = await fetch('api/catalogo/list.php');
        const result = await response.json();
        
        if (result.success) {
            renderCatalogo(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar catálogo:', error);
    }
}

// Renderizar catálogo
function renderCatalogo(items) {
    if (items.length === 0) {
        catalogoGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Nenhum card adicionado ao catálogo</p>';
        return;
    }
    
    catalogoGrid.innerHTML = items.map(item => `
        <div class="catalog-card">
            <img src="../${item.Imagem}" alt="${item.Nome}" class="catalog-card-image" onerror="this.src='https://via.placeholder.com/280x200'">
            <div class="catalog-card-content">
                <h3>${item.Nome}</h3>
                <p>${item.Descricao || 'Sem descrição'}</p>
            </div>
            <div class="catalog-card-actions">
                <button class="btn-icon btn-edit" onclick="editCatalogo(${item.ID})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </button>
                <button class="btn-icon btn-delete" onclick="deleteCatalogo(${item.ID}, '${item.Nome.replace(/'/g, "\\'")}')">
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
        
        const response = await fetch('api/catalogo/upload.php', {
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

// Upload PDF
async function uploadPdf(file) {
    try {
        const formData = new FormData();
        formData.append('pdf', file);
        
        const response = await fetch('api/catalogo/upload-pdf.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            uploadedPdfPath = result.path;
            pdfPath.value = result.path;
            showSuccess('PDF enviado com sucesso');
        } else {
            showError(result.message || 'Erro ao enviar PDF');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao enviar PDF');
    }
}

// Criar card
async function createCatalogo(data) {
    try {
        const response = await fetch('api/catalogo/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Card adicionado ao catálogo com sucesso');
            closeModal();
            loadCatalogo();
        } else {
            showError(result.message || 'Erro ao adicionar card');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao adicionar card');
    }
}

// Atualizar card
async function updateCatalogo(data) {
    try {
        const response = await fetch('api/catalogo/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Card atualizado com sucesso');
            closeModal();
            loadCatalogo();
        } else {
            showError(result.message || 'Erro ao atualizar card');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao atualizar card');
    }
}

// Editar card
async function editCatalogo(id) {
    try {
        const response = await fetch('api/catalogo/list.php');
        const result = await response.json();
        
        if (result.success) {
            const item = result.data.find(c => c.ID == id);
            if (item) {
                catalogoId.value = item.ID;
                nome.value = item.Nome;
                descricao.value = item.Descricao || '';
                imagemPath.value = item.Imagem;
                imagePreview.src = '../' + item.Imagem;
                imagePreview.style.display = 'block';
                uploadedImagePath = item.Imagem;
                
                pdfPath.value = item.PDF || '';
                uploadedPdfPath = item.PDF || '';
                if (item.PDF) {
                    const pdfName = item.PDF.split('/').pop();
                    pdfFileName.textContent = `PDF atual: ${pdfName}`;
                }
                
                modalTitle.textContent = 'Editar Card';
                modal.classList.add('show');
            }
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

// Eliminar card
async function deleteCatalogo(id, nomeItem) {
    if (!confirm(`Tem certeza que deseja eliminar "${nomeItem}"?`)) return;
    
    try {
        const response = await fetch('api/catalogo/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Card eliminado com sucesso');
            loadCatalogo();
        } else {
            showError(result.message || 'Erro ao eliminar card');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao eliminar card');
    }
}

// Abrir modal
function openModal() {
    catalogoId.value = '';
    nome.value = '';
    descricao.value = '';
    imagemPath.value = '';
    imagemFile.value = '';
    imagePreview.style.display = 'none';
    uploadedImagePath = '';
    pdfPath.value = '';
    pdfFile.value = '';
    pdfFileName.textContent = '';
    uploadedPdfPath = '';
    modalTitle.textContent = 'Adicionar Card';
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
window.editCatalogo = editCatalogo;
window.deleteCatalogo = deleteCatalogo;
