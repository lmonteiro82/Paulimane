/**
 * Gestão de Equipa
 */

// Elementos
const teamGrid = document.getElementById('teamGrid');
const modal = document.getElementById('modal');
const teamForm = document.getElementById('teamForm');
const btnAdd = document.getElementById('btnAdd');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');
const memberId = document.getElementById('memberId');
const imagemFile = document.getElementById('imagemFile');
const imagePreview = document.getElementById('imagePreview');
const imagemPath = document.getElementById('imagemPath');
const nome = document.getElementById('nome');
const funcao = document.getElementById('funcao');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');

let uploadedImagePath = '';

// Carregar membros ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadMembros();
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
    
    console.log('Ficheiro selecionado:', file.name, 'Tamanho:', (file.size / 1024 / 1024).toFixed(2) + 'MB');
    
    // Mostrar preview
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.src = e.target.result;
        imagePreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
    
    // Comprimir se for muito grande (> 2MB)
    let fileToUpload = file;
    if (file.size > 2 * 1024 * 1024) {
        console.log('Imagem grande detectada, a comprimir...');
        fileToUpload = await compressImage(file);
        console.log('Imagem comprimida:', (fileToUpload.size / 1024 / 1024).toFixed(2) + 'MB');
    }
    
    // Upload imagem
    await uploadImage(fileToUpload);
});

// Submit formulário
teamForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = memberId.value;
    const nomeVal = nome.value.trim();
    const funcaoVal = funcao.value.trim();
    const imagemVal = imagemPath.value || uploadedImagePath;
    
    console.log('Dados do formulário:', { id, nomeVal, funcaoVal, imagemVal }); // DEBUG
    
    if (!nomeVal) {
        showError('Por favor, preencha o nome');
        return;
    }
    
    if (!funcaoVal) {
        showError('Por favor, preencha a função');
        return;
    }
    
    if (!imagemVal) {
        showError('Por favor, faça upload da imagem');
        return;
    }
    
    const data = {
        nome: nomeVal,
        funcao: funcaoVal,
        imagem: imagemVal
    };
    
    console.log('Enviando dados:', data); // DEBUG
    
    if (id) {
        data.id = id;
        await updateMembro(data);
    } else {
        await createMembro(data);
    }
});

// Carregar membros
async function loadMembros() {
    try {
        const response = await fetch('api/equipa/list.php');
        const result = await response.json();
        
        if (result.success) {
            renderMembros(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar membros:', error);
    }
}

// Renderizar membros
function renderMembros(membros) {
    console.log('Membros:', membros); // DEBUG
    
    if (membros.length === 0) {
        teamGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Nenhum membro adicionado</p>';
        return;
    }
    
    teamGrid.innerHTML = membros.map(membro => `
        <div class="team-card">
            <img src="../${membro.Imagem || membro.imagem}" alt="${membro.Nome || membro.nome}" onerror="this.src='https://via.placeholder.com/150'" style="width: 150px; height: 150px; object-fit: cover;">
            <h3>${membro.Nome || membro.nome}</h3>
            <p>${membro.Funcao || membro.funcao || 'Sem função'}</p>
            <div class="team-card-actions">
                <button class="btn-icon btn-edit" onclick="editMembro(${membro.ID})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </button>
                <button class="btn-icon btn-delete" onclick="deleteMembro(${membro.ID}, '${membro.Nome}')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');
}

// Comprimir imagem
async function compressImage(file) {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                
                // Redimensionar se for muito grande
                const maxDimension = 1920;
                if (width > maxDimension || height > maxDimension) {
                    if (width > height) {
                        height = (height / width) * maxDimension;
                        width = maxDimension;
                    } else {
                        width = (width / height) * maxDimension;
                        height = maxDimension;
                    }
                }
                
                canvas.width = width;
                canvas.height = height;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // Converter para blob com qualidade reduzida
                canvas.toBlob((blob) => {
                    const compressedFile = new File([blob], file.name, {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    resolve(compressedFile);
                }, 'image/jpeg', 0.8); // 80% de qualidade
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

// Upload imagem
async function uploadImage(file) {
    try {
        console.log('Iniciando upload de imagem:', file);
        
        if (!file) {
            showError('Nenhum ficheiro selecionado');
            return;
        }
        
        const formData = new FormData();
        formData.append('imagem', file);
        
        console.log('FormData criado, enviando para API...');
        
        const response = await fetch('api/equipa/upload.php', {
            method: 'POST',
            body: formData
        });
        
        console.log('Resposta recebida:', response.status);
        
        const result = await response.json();
        console.log('Resultado:', result);
        
        if (result.success) {
            uploadedImagePath = result.path;
            imagemPath.value = result.path;
            showSuccess('Imagem enviada com sucesso');
        } else {
            showError(result.message || 'Erro ao enviar imagem');
        }
    } catch (error) {
        console.error('Erro no upload:', error);
        showError('Erro ao enviar imagem: ' + error.message);
    }
}

// Criar membro
async function createMembro(data) {
    try {
        const response = await fetch('api/equipa/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Membro adicionado com sucesso');
            closeModal();
            loadMembros();
        } else {
            showError(result.message || 'Erro ao adicionar membro');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao adicionar membro');
    }
}

// Atualizar membro
async function updateMembro(data) {
    try {
        const response = await fetch('api/equipa/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Membro atualizado com sucesso');
            closeModal();
            loadMembros();
        } else {
            showError(result.message || 'Erro ao atualizar membro');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao atualizar membro');
    }
}

// Editar membro
async function editMembro(id) {
    try {
        const response = await fetch('api/equipa/list.php');
        const result = await response.json();
        
        console.log('Resultado da API:', result); // DEBUG
        
        if (result.success) {
            const membro = result.data.find(m => m.ID == id);
            console.log('Membro encontrado:', membro); // DEBUG
            
            if (membro) {
                memberId.value = membro.ID || membro.id;
                nome.value = membro.Nome || membro.nome;
                funcao.value = membro.Funcao || membro.funcao || ''; // Tentar ambas as variações
                imagemPath.value = membro.Imagem || membro.imagem;
                imagePreview.src = '../' + (membro.Imagem || membro.imagem);
                imagePreview.style.display = 'block';
                uploadedImagePath = membro.Imagem || membro.imagem;
                
                console.log('Valores preenchidos:', {
                    nome: nome.value,
                    funcao: funcao.value,
                    imagem: imagemPath.value
                }); // DEBUG
                
                modalTitle.textContent = 'Editar Membro';
                modal.classList.add('show');
            }
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

// Eliminar membro
async function deleteMembro(id, nome) {
    if (!confirm(`Tem certeza que deseja eliminar ${nome}?`)) return;
    
    try {
        const response = await fetch('api/equipa/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Membro eliminado com sucesso');
            loadMembros();
        } else {
            showError(result.message || 'Erro ao eliminar membro');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao eliminar membro');
    }
}

// Abrir modal
function openModal() {
    memberId.value = '';
    nome.value = '';
    funcao.value = '';
    imagemPath.value = '';
    imagemFile.value = '';
    imagePreview.style.display = 'none';
    uploadedImagePath = '';
    modalTitle.textContent = 'Adicionar Membro';
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
window.editMembro = editMembro;
window.deleteMembro = deleteMembro;
