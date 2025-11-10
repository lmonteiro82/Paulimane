/**
 * Gestão de Clientes
 */

// Elementos
const clientesGrid = document.getElementById('clientesGrid');
const modal = document.getElementById('modal');
const clienteForm = document.getElementById('clienteForm');
const btnAdd = document.getElementById('btnAdd');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');
const clienteId = document.getElementById('clienteId');
const imagemFile = document.getElementById('imagemFile');
const imagePreview = document.getElementById('imagePreview');
const imagemPath = document.getElementById('imagemPath');
const name = document.getElementById('name');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');

let uploadedImagePath = '';

// Carregar clientes ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadClientes();
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
clienteForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = clienteId.value;
    const nameVal = name.value.trim();
    const imagemVal = imagemPath.value || uploadedImagePath;
    
    if (!nameVal) {
        showError('Por favor, preencha o nome do cliente');
        return;
    }
    
    if (!imagemVal) {
        showError('Por favor, faça upload do logo');
        return;
    }
    
    const data = {
        name: nameVal,
        imagem: imagemVal
    };
    
    if (id) {
        data.id = id;
        await updateCliente(data);
    } else {
        await createCliente(data);
    }
});

// Carregar clientes
async function loadClientes() {
    try {
        const response = await fetch('api/clientes/list.php');
        const result = await response.json();
        
        if (result.success) {
            renderClientes(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar clientes:', error);
    }
}

// Renderizar clientes
function renderClientes(clientes) {
    if (clientes.length === 0) {
        clientesGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Nenhum cliente adicionado</p>';
        return;
    }
    
    clientesGrid.innerHTML = clientes.map(cliente => `
        <div class="team-card">
            <img src="../${cliente.imagem}" alt="${cliente.Nome || cliente.Name}" onerror="this.src='https://via.placeholder.com/150'" style="width: 150px; height: 150px; object-fit: contain; background: #f5f5f5; padding: 10px;">
            <h3>${cliente.Nome || cliente.Name || 'Sem nome'}</h3>
            <div class="team-card-actions">
                <button class="btn-icon btn-edit" onclick="editCliente(${cliente.ID})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </button>
                <button class="btn-icon btn-delete" onclick="deleteCliente(${cliente.ID}, '${cliente.Nome || cliente.Name}')">
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
        
        const response = await fetch('api/clientes/upload.php', {
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

// Criar cliente
async function createCliente(data) {
    try {
        const response = await fetch('api/clientes/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Cliente adicionado com sucesso');
            closeModal();
            loadClientes();
        } else {
            showError(result.message || 'Erro ao adicionar cliente');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao adicionar cliente');
    }
}

// Atualizar cliente
async function updateCliente(data) {
    try {
        const response = await fetch('api/clientes/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Cliente atualizado com sucesso');
            closeModal();
            loadClientes();
        } else {
            showError(result.message || 'Erro ao atualizar cliente');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao atualizar cliente');
    }
}

// Editar cliente
async function editCliente(id) {
    try {
        const response = await fetch('api/clientes/list.php');
        const result = await response.json();
        
        if (result.success) {
            const cliente = result.data.find(c => c.ID == id);
            if (cliente) {
                clienteId.value = cliente.ID;
                name.value = cliente.Nome || cliente.Name;
                imagemPath.value = cliente.imagem;
                imagePreview.src = '../' + cliente.imagem;
                imagePreview.style.display = 'block';
                uploadedImagePath = cliente.imagem;
                
                modalTitle.textContent = 'Editar Cliente';
                modal.classList.add('show');
            }
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

// Eliminar cliente
async function deleteCliente(id, nome) {
    if (!confirm(`Tem certeza que deseja eliminar ${nome}?`)) return;
    
    try {
        const response = await fetch('api/clientes/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccess('Cliente eliminado com sucesso');
            loadClientes();
        } else {
            showError(result.message || 'Erro ao eliminar cliente');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao eliminar cliente');
    }
}

// Abrir modal
function openModal() {
    clienteId.value = '';
    name.value = '';
    imagemPath.value = '';
    imagemFile.value = '';
    imagePreview.style.display = 'none';
    uploadedImagePath = '';
    modalTitle.textContent = 'Adicionar Cliente';
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
window.editCliente = editCliente;
window.deleteCliente = deleteCliente;
