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
const btnUploadLargePdf = document.getElementById('btnUploadLargePdf');
const pdfFileLarge = document.getElementById('pdfFileLarge');
const uploadProgress = document.getElementById('uploadProgress');
const uploadProgressBar = document.getElementById('uploadProgressBar');
const uploadProgressPercent = document.getElementById('uploadProgressPercent');
const uploadProgressText = document.getElementById('uploadProgressText');
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

// Botão upload PDF
btnUploadLargePdf.addEventListener('click', () => {
    pdfFileLarge.click();
});

// Upload de PDF (chunked)
pdfFileLarge.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    await uploadPdfChunked(file);
});


// Submit formulário
catalogoForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = catalogoId.value;
    const nomeVal = nome.value.trim();
    const descricaoVal = descricao.value.trim();
    const imagemVal = imagemPath.value || uploadedImagePath;
    const pdfVal = pdfPath.value || uploadedPdfPath;
    
    console.log('Submit - Nome:', nomeVal);
    console.log('Submit - Imagem:', imagemVal);
    console.log('Submit - PDF:', pdfVal);
    
    if (!nomeVal) {
        showError('Por favor, preencha o nome do card');
        return;
    }
    
    if (!imagemVal) {
        showError('Por favor, faça upload da imagem primeiro');
        return;
    }
    
    if (!pdfVal) {
        showError('Por favor, faça upload do PDF primeiro');
        return;
    }
    
    const data = {
        nome: nomeVal,
        descricao: descricaoVal,
        imagem: imagemVal,
        pdf: pdfVal
    };
    
    console.log('Enviando dados:', data);
    
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
        console.log('Iniciando upload de imagem:', file.name, file.type, file.size);
        
        const formData = new FormData();
        formData.append('imagem', file);
        
        const response = await fetch('api/catalogo/upload.php', {
            method: 'POST',
            body: formData
        });
        
        console.log('Resposta do upload:', response.status);
        
        const result = await response.json();
        console.log('Resultado do upload:', result);
        
        if (result.success) {
            uploadedImagePath = result.path;
            imagemPath.value = result.path;
            console.log('Imagem carregada com sucesso:', uploadedImagePath);
            showSuccess('Imagem enviada com sucesso');
        } else {
            console.error('Erro no upload:', result.message);
            showError(result.message || 'Erro ao enviar imagem');
            // Limpar preview em caso de erro
            imagePreview.style.display = 'none';
            imagemFile.value = '';
        }
    } catch (error) {
        console.error('Erro no upload:', error);
        showError('Erro ao enviar imagem');
        // Limpar preview em caso de erro
        imagePreview.style.display = 'none';
        imagemFile.value = '';
    }
}

// Upload PDF
async function uploadPdf(file) {
    try {
        console.log('Iniciando upload de PDF:', file.name, file.type, file.size);
        
        const formData = new FormData();
        formData.append('pdf', file);
        
        const response = await fetch('api/catalogo/upload-pdf.php', {
            method: 'POST',
            body: formData
        });
        
        console.log('Resposta do upload PDF:', response.status);
        
        const result = await response.json();
        console.log('Resultado do upload PDF:', result);
        
        if (result.success) {
            uploadedPdfPath = result.path;
            pdfPath.value = result.path;
            console.log('PDF carregado com sucesso:', uploadedPdfPath);
            showSuccess('PDF enviado com sucesso');
        } else {
            console.error('Erro no upload PDF:', result.message);
            showError(result.message || 'Erro ao enviar PDF');
            // Limpar em caso de erro
            pdfFileName.textContent = '';
            pdfFile.value = '';
        }
    } catch (error) {
        console.error('Erro no upload PDF:', error);
        showError('Erro ao enviar PDF');
        // Limpar em caso de erro
        pdfFileName.textContent = '';
        pdfFile.value = '';
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
    pdfFileLarge.value = '';
    pdfFileName.textContent = '';
    uploadedPdfPath = '';
    uploadProgress.style.display = 'none';
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


// Upload de PDF em chunks (para arquivos grandes)
async function uploadPdfChunked(file) {
    const chunkSize = 1024 * 1024; // 1MB por chunk
    const chunks = Math.ceil(file.size / chunkSize);
    
    uploadProgress.style.display = 'block';
    uploadProgressText.textContent = `Enviando ${file.name}...`;
    uploadProgressPercent.textContent = '0%';
    uploadProgressBar.style.width = '0%';
    
    try {
        for (let chunk = 0; chunk < chunks; chunk++) {
            const start = chunk * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const chunkBlob = file.slice(start, end);
            
            const formData = new FormData();
            formData.append('file', chunkBlob);
            formData.append('chunk', chunk);
            formData.append('chunks', chunks);
            formData.append('filename', file.name);
            
            const response = await fetch('api/catalogo/upload-pdf-chunked.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Erro no upload');
            }
            
            // Atualizar progresso
            const progress = Math.round(((chunk + 1) / chunks) * 100);
            uploadProgressPercent.textContent = `${progress}%`;
            uploadProgressBar.style.width = `${progress}%`;
            
            // Se é o último chunk, o upload está completo
            if (chunk === chunks - 1 && result.path) {
                uploadedPdfPath = result.path;
                pdfPath.value = result.path;
                
                uploadProgressText.textContent = '✓ Upload completo!';
                
                pdfFileName.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    PDF enviado: ${result.filename} (${result.size_mb} MB)
                `;
                
                showSuccess(`PDF enviado com sucesso! (${result.size_mb} MB)`);
                
                // Esconder barra de progresso após 2 segundos
                setTimeout(() => {
                    uploadProgress.style.display = 'none';
                }, 2000);
            }
        }
    } catch (error) {
        console.error('Erro no upload:', error);
        uploadProgress.style.display = 'none';
        showError(error.message || 'Erro ao enviar PDF');
    }
}


// Expor funções globalmente
window.editCatalogo = editCatalogo;
window.deleteCatalogo = deleteCatalogo;
