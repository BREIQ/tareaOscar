// Elementos del DOM
const attendanceBtn = document.getElementById('attendanceBtn');
const adminBtn = document.getElementById('adminBtn');
const pinModal = document.getElementById('pinModal');
const adminModal = document.getElementById('adminModal');
const closePinModal = document.getElementById('closePinModal');
const closeAdminModal = document.getElementById('closeAdminModal');
const pinInput = document.getElementById('pinInput');
const deleteBtn = document.getElementById('deleteBtn');
const submitPinBtn = document.getElementById('submitPinBtn');
const cancelPinBtn = document.getElementById('cancelPinBtn');
const submitAdminBtn = document.getElementById('submitAdminBtn');
const cancelAdminBtn = document.getElementById('cancelAdminBtn');
const pinMessage = document.getElementById('pinMessage');
const adminMessage = document.getElementById('adminMessage');
const adminPassword = document.getElementById('adminPassword');

// Variables
let pinValue = '';

// ===== EVENTOS PRINCIPALES =====

attendanceBtn.addEventListener('click', () => {
    pinValue = '';
    pinInput.value = '';
    pinMessage.classList.add('hidden');
    submitPinBtn.disabled = true;
    pinModal.classList.remove('hidden');
});

adminBtn.addEventListener('click', () => {
    adminPassword.value = '';
    adminMessage.classList.add('hidden');
    adminModal.classList.remove('hidden');
});

closePinModal.addEventListener('click', () => {
    pinModal.classList.add('hidden');
});

closeAdminModal.addEventListener('click', () => {
    adminModal.classList.add('hidden');
});

cancelPinBtn.addEventListener('click', () => {
    pinModal.classList.add('hidden');
});

cancelAdminBtn.addEventListener('click', () => {
    adminModal.classList.add('hidden');
});

// ===== TECLADO NUMÉRICO =====

document.querySelectorAll('.key:not(.empty)').forEach(key => {
    key.addEventListener('click', () => {
        const value = key.getAttribute('data-value');
        
        if (value && pinValue.length < 6) {
            pinValue += value;
            updatePinDisplay();
            
            if (pinValue.length === 6) {
                submitPinBtn.disabled = false;
            }
        }
    });
});

deleteBtn.addEventListener('click', () => {
    if (pinValue.length > 0) {
        pinValue = pinValue.slice(0, -1);
        updatePinDisplay();
        submitPinBtn.disabled = true;
    }
});

function updatePinDisplay() {
    const display = pinValue.padEnd(6, '0').slice(0, 6);
    pinInput.value = display;
}

// ===== REGISTRO DE ASISTENCIA =====

submitPinBtn.addEventListener('click', async () => {
    if (pinValue.length !== 6) {
        showPinMessage('Por favor ingrese 6 dígitos', 'error');
        return;
    }
    
    submitPinBtn.disabled = true;
    showPinMessage('Registrando asistencia...', 'info');
    
    try {
        const response = await fetch('/api/attendance/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ pin: pinValue })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showPinMessage(data.message, 'success');
            setTimeout(() => {
                pinModal.classList.add('hidden');
                pinValue = '';
                pinInput.value = '';
                submitPinBtn.disabled = true;
            }, 3000);
        } else {
            showPinMessage(data.message, 'error');
            submitPinBtn.disabled = false;
        }
    } catch (error) {
        showPinMessage('Error al conectar con el servidor', 'error');
        submitPinBtn.disabled = false;
    }
});

// ===== VALIDACIÓN DE ADMINISTRADOR =====

submitAdminBtn.addEventListener('click', async () => {
    const password = adminPassword.value;
    
    if (!password) {
        showAdminMessage('Ingrese la contraseña', 'error');
        return;
    }
    
    submitAdminBtn.disabled = true;
    showAdminMessage('Validando...', 'info');
    
    try {
        const response = await fetch('/api/admin/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAdminMessage('Acceso permitido', 'success');
            setTimeout(() => {
                window.location.href = '/admin';
            }, 1000);
        } else {
            showAdminMessage('Contraseña incorrecta', 'error');
            submitAdminBtn.disabled = false;
        }
    } catch (error) {
        showAdminMessage('Error al conectar con el servidor', 'error');
        submitAdminBtn.disabled = false;
    }
});

// Permitir Enter en contraseña
adminPassword.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        submitAdminBtn.click();
    }
});

// ===== FUNCIONES AUXILIARES =====

function showPinMessage(message, type) {
    pinMessage.textContent = message;
    pinMessage.className = `message ${type}`;
    pinMessage.classList.remove('hidden');
}

function showAdminMessage(message, type) {
    adminMessage.textContent = message;
    adminMessage.className = `message ${type}`;
    adminMessage.classList.remove('hidden');
}

// Cerrar modales al hacer click fuera de ellos
pinModal.addEventListener('click', (e) => {
    if (e.target === pinModal) {
        pinModal.classList.add('hidden');
    }
});

adminModal.addEventListener('click', (e) => {
    if (e.target === adminModal) {
        adminModal.classList.add('hidden');
    }
});
