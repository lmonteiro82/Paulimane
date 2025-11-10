// Toggle Password Visibility
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = togglePassword.querySelector('.eye-icon');
const eyeOffIcon = togglePassword.querySelector('.eye-off-icon');

if (togglePassword) {
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icons
        if (type === 'text') {
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    });
}

// Login Form Handler
const loginForm = document.getElementById('loginForm');
const loginBtn = document.getElementById('loginBtn');
const btnText = loginBtn.querySelector('.btn-text');
const btnLoader = loginBtn.querySelector('.btn-loader');
const errorAlert = document.getElementById('errorAlert');
const errorMessage = document.getElementById('errorMessage');

function showError(message) {
    errorMessage.textContent = message;
    errorAlert.style.display = 'flex';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        errorAlert.style.display = 'none';
    }, 5000);
}

function hideError() {
    errorAlert.style.display = 'none';
}

function setLoading(isLoading) {
    loginBtn.disabled = isLoading;
    
    if (isLoading) {
        btnText.style.display = 'none';
        btnLoader.style.display = 'block';
    } else {
        btnText.style.display = 'block';
        btnLoader.style.display = 'none';
    }
}

if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideError();
        
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        
        // Validação básica
        if (!username || !password) {
            showError('Por favor, preencha todos os campos');
            return;
        }
        
        // Ativar loading
        setLoading(true);
        
        try {
            // Fazer chamada à API de login
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                })
            });

            const data = await response.json();

            if (data.success) {
                // Login bem-sucedido
                
                // Guardar token
                sessionStorage.setItem('paulimane_auth_token', data.data.token);
                sessionStorage.setItem('paulimane_user', JSON.stringify(data.data.user));
                
                // Mostrar mensagem de sucesso e redirecionar
                showSuccessAndRedirect();
            } else {
                // Login falhou
                setLoading(false);
                showError(data.message || 'Utilizador ou password incorretos');
                
                // Limpar password
                passwordInput.value = '';
                passwordInput.focus();
            }
        } catch (error) {
            console.error('Erro ao fazer login:', error);
            setLoading(false);
            showError('Erro ao conectar ao servidor. Verifique se o servidor PHP está ativo.');
        }
    });
}

function showSuccessAndRedirect() {
    // Mudar botão para sucesso
    loginBtn.style.background = 'var(--success)';
    btnLoader.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    `;
    
    // Redirecionar após 1 segundo
    setTimeout(() => {
        // Redirecionar para gestão de utilizadores
        window.location.href = 'utilizadores.html';
    }, 1000);
}

// Check if user is already logged in
window.addEventListener('DOMContentLoaded', () => {
    const authToken = sessionStorage.getItem('paulimane_auth_token');
    const rememberMe = localStorage.getItem('paulimane_remember');
    
    if (authToken || rememberMe) {
        // User is already logged in, redirect to utilizadores
        // window.location.href = 'utilizadores.html';
    }
    
    // Auto-fill username if remembered
    if (rememberMe) {
        const savedUsername = localStorage.getItem('paulimane_username');
        if (savedUsername) {
            document.getElementById('username').value = savedUsername;
            document.getElementById('rememberMe').checked = true;
        }
    }
});

// Prevent form submission on Enter in password field (already handled by form submit)
passwordInput?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        loginForm.dispatchEvent(new Event('submit'));
    }
});

// Clear error on input
document.getElementById('username')?.addEventListener('input', hideError);
document.getElementById('password')?.addEventListener('input', hideError);
