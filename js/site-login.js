/**
 * Sistema de Login do Site Paulimane
 */

// Verificar se já está autenticado
if (sessionStorage.getItem('paulimane_site_auth')) {
    window.location.href = 'index.php';
}

// Elementos
const loginForm = document.getElementById('loginForm');
const loginBtn = document.getElementById('loginBtn');
const errorAlert = document.getElementById('errorAlert');
const errorMessage = document.getElementById('errorMessage');
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const emailInput = document.getElementById('email');

// Toggle password visibility
togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Trocar ícone
    const icon = togglePassword.querySelector('svg');
    if (type === 'text') {
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
    } else {
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    }
});

// Form submission
loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = emailInput.value.trim();
    const password = passwordInput.value;
    
    // Validação básica
    if (!email || !password) {
        showError('Por favor, preencha todos os campos');
        return;
    }
    
    // Validar formato de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError('Por favor, insira um email válido');
        return;
    }
    
    // Ativar loading
    setLoading(true);
    hideError();
    
    try {
        // Fazer chamada à API de login
        const response = await fetch('backoffice/api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                username: email,
                password: password
            })
        });

        const data = await response.json();

        if (data.success) {
            // Login bem-sucedido
            sessionStorage.setItem('paulimane_site_auth', data.data.token);
            sessionStorage.setItem('paulimane_site_user', JSON.stringify(data.data.user));
            
            // Mostrar mensagem de sucesso
            showSuccess();
            
            // Redirecionar após 1 segundo
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        } else {
            // Login falhou
            setLoading(false);
            showError(data.message || 'Email ou password incorretos');
            
            // Limpar password
            passwordInput.value = '';
            passwordInput.focus();
        }
    } catch (error) {
        console.error('Erro ao fazer login:', error);
        setLoading(false);
        showError('Erro ao conectar ao servidor. Por favor, tente novamente.');
    }
});

// Funções auxiliares
function showError(message) {
    errorMessage.textContent = message;
    errorAlert.style.display = 'flex';
}

function hideError() {
    errorAlert.style.display = 'none';
}

function setLoading(loading) {
    const btnText = loginBtn.querySelector('.btn-text');
    const btnLoader = loginBtn.querySelector('.btn-loader');
    
    if (loading) {
        btnText.style.display = 'none';
        btnLoader.style.display = 'inline-block';
        loginBtn.disabled = true;
    } else {
        btnText.style.display = 'inline';
        btnLoader.style.display = 'none';
        loginBtn.disabled = false;
    }
}

function showSuccess() {
    errorAlert.style.display = 'flex';
    errorAlert.className = 'alert';
    errorAlert.style.backgroundColor = '#d4edda';
    errorAlert.style.color = '#155724';
    errorAlert.style.borderColor = '#c3e6cb';
    
    errorAlert.querySelector('svg').innerHTML = '<polyline points="20 6 9 17 4 12"></polyline>';
    errorMessage.textContent = 'Login realizado com sucesso! Redirecionando...';
}

// Enter no email vai para password
emailInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        passwordInput.focus();
    }
});
