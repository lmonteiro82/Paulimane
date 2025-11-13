/**
 * Gestão de Utilizadores
 * Paulimane Backoffice
 */

// Elementos
const btnNewUser = document.getElementById('btnNewUser');
const userModal = document.getElementById('userModal');
const btnCloseModal = document.getElementById('btnCloseModal');
const btnCancelModal = document.getElementById('btnCancelModal');
const userForm = document.getElementById('userForm');
const usersTableBody = document.getElementById('usersTableBody');
const usersTable = document.getElementById('usersTable');
const loadingState = document.getElementById('loadingState');
const emptyState = document.getElementById('emptyState');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');
const successMessage = document.getElementById('successMessage');
const errorMessage = document.getElementById('errorMessage');
const modalTitle = document.getElementById('modalTitle');
const passwordHelp = document.getElementById('passwordHelp');

let editingUserId = null;

// Carregar utilizadores ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
});

// Abrir modal para novo utilizador
btnNewUser.addEventListener('click', () => {
    editingUserId = null;
    modalTitle.textContent = 'Novo Utilizador';
    userForm.reset();
    document.getElementById('userId').value = '';
    document.getElementById('userPassword').required = true;
    passwordHelp.style.display = 'none';
    openModal();
});

// Fechar modal
btnCloseModal.addEventListener('click', closeModal);
btnCancelModal.addEventListener('click', closeModal);

// Fechar modal ao clicar fora
userModal.addEventListener('click', (e) => {
    if (e.target === userModal) {
        closeModal();
    }
});

// Submit do formulário
userForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = {
        nome: document.getElementById('userNameInput').value.trim(),
        email: document.getElementById('userEmail').value.trim(),
        password: document.getElementById('userPassword').value,
        nivel: document.getElementById('userNivel').value,
        ativo: document.getElementById('userStatus').value
    };

    // Validações
    if (!formData.nome || !formData.email || !formData.nivel) {
        showError('Por favor, preencha todos os campos obrigatórios');
        return;
    }

    if (!editingUserId && !formData.password) {
        showError('Por favor, defina uma password');
        return;
    }

    if (formData.password && formData.password.length < 6) {
        showError('A password deve ter no mínimo 6 caracteres');
        return;
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
        showError('Por favor, insira um email válido');
        return;
    }

    try {
        let response;
        
        if (editingUserId) {
            // Editar utilizador
            formData.id = editingUserId;
            
            // Se password estiver vazia, não enviar
            if (!formData.password) {
                delete formData.password;
            }
            
            response = await fetch('api/users/update.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
        } else {
            // Criar novo utilizador
            response = await fetch('api/users/create.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
        }

        const data = await response.json();

        if (data.success) {
            showSuccess(data.message || (editingUserId ? 'Utilizador atualizado com sucesso' : 'Utilizador criado com sucesso'));
            closeModal();
            loadUsers();
        } else {
            showError(data.message || 'Erro ao guardar utilizador');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao conectar ao servidor');
    }
});

// Carregar utilizadores
async function loadUsers() {
    try {
        loadingState.style.display = 'block';
        usersTable.style.display = 'none';
        emptyState.style.display = 'none';

        const response = await fetch('api/users/list.php');
        const data = await response.json();

        loadingState.style.display = 'none';

        if (data.success && data.users && data.users.length > 0) {
            renderUsers(data.users);
            usersTable.style.display = 'table';
        } else {
            emptyState.style.display = 'block';
        }
    } catch (error) {
        console.error('Erro ao carregar utilizadores:', error);
        loadingState.style.display = 'none';
        showError('Erro ao carregar utilizadores');
    }
}

// Renderizar utilizadores na tabela
function renderUsers(users) {
    usersTableBody.innerHTML = '';

    users.forEach(user => {
        const tr = document.createElement('tr');
        
        const statusClass = user.Ativo == 1 ? 'status-active' : 'status-inactive';
        const statusText = user.Ativo == 1 ? 'Ativo' : 'Inativo';
        
        const nivelText = user.Nivel == 1 ? 'Nível 1' : user.Nivel == 2 ? 'Nível 2' : 'Nível 3';
        const nivelClass = user.Nivel == 3 ? 'nivel-admin' : user.Nivel == 2 ? 'nivel-editor' : 'nivel-basico';

        tr.innerHTML = `
            <td><strong>${escapeHtml(user.Nome)}</strong></td>
            <td>${escapeHtml(user.Email)}</td>
            <td><span class="status-badge ${nivelClass}">${nivelText}</span></td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn-icon edit" onclick="editUser(${user.ID})" title="Editar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                    <button class="btn-icon delete" onclick="deleteUser(${user.ID}, '${escapeHtml(user.Nome)}')" title="Eliminar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
            </td>
        `;

        usersTableBody.appendChild(tr);
    });
}

// Editar utilizador
async function editUser(id) {
    try {
        const response = await fetch(`api/users/get.php?id=${id}`);
        const data = await response.json();

        if (data.success && data.user) {
            editingUserId = id;
            modalTitle.textContent = 'Editar Utilizador';
            
            document.getElementById('userId').value = data.user.ID;
            document.getElementById('userNameInput').value = data.user.Nome;
            document.getElementById('userEmail').value = data.user.Email;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
            document.getElementById('userNivel').value = data.user.Nivel || 1;
            document.getElementById('userStatus').value = data.user.Ativo;
            
            passwordHelp.style.display = 'block';
            
            openModal();
        } else {
            showError('Erro ao carregar utilizador');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao conectar ao servidor');
    }
}

// Eliminar utilizador
async function deleteUser(id, nome) {
    if (!confirm(`Tem a certeza que deseja eliminar o utilizador "${nome}"?`)) {
        return;
    }

    try {
        const response = await fetch('api/users/delete.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        });

        const data = await response.json();

        if (data.success) {
            showSuccess('Utilizador eliminado com sucesso');
            loadUsers();
        } else {
            showError(data.message || 'Erro ao eliminar utilizador');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro ao conectar ao servidor');
    }
}

// Funções auxiliares
function openModal() {
    userModal.classList.add('active');
}

function closeModal() {
    userModal.classList.remove('active');
    userForm.reset();
    editingUserId = null;
}

function showSuccess(message) {
    successMessage.textContent = message;
    successAlert.classList.add('show');
    errorAlert.classList.remove('show');
    
    setTimeout(() => {
        successAlert.classList.remove('show');
    }, 5000);
}

function showError(message) {
    errorMessage.textContent = message;
    errorAlert.classList.add('show');
    successAlert.classList.remove('show');
    
    setTimeout(() => {
        errorAlert.classList.remove('show');
    }, 5000);
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Expor funções globalmente
window.editUser = editUser;
window.deleteUser = deleteUser;
