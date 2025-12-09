<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asistencia</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="home-wrapper">
            <div class="header">
                <h1>Sistema de Asistencia</h1>
                <p class="subtitle">Registro de Alumnos</p>
            </div>
            
            <div class="button-group">
                <button id="attendanceBtn" class="btn btn-primary btn-lg">
                    Registrar Asistencia
                </button>
                <button id="adminBtn" class="btn btn-secondary">
                    Acceso Admin
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para PIN -->
    <div id="pinModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ingrese PIN</h2>
                <button class="close-btn" id="closePinModal">&times;</button>
            </div>
            
            <div class="pin-input-group">
                <input 
                    type="text" 
                    id="pinInput" 
                    class="pin-display" 
                    placeholder="000000" 
                    readonly
                    maxlength="6"
                >
            </div>
            
            <div class="keyboard-grid">
                <button class="key" data-value="1">1</button>
                <button class="key" data-value="2">2</button>
                <button class="key" data-value="3">3</button>
                <button class="key" data-value="4">4</button>
                <button class="key" data-value="5">5</button>
                <button class="key" data-value="6">6</button>
                <button class="key" data-value="7">7</button>
                <button class="key" data-value="8">8</button>
                <button class="key" data-value="9">9</button>
                <button class="key empty"></button>
                <button class="key" data-value="0">0</button>
                <button class="key key-delete" id="deleteBtn">←</button>
            </div>
            
            <div class="modal-actions">
                <button id="submitPinBtn" class="btn btn-primary" disabled>Enviar</button>
                <button id="cancelPinBtn" class="btn btn-secondary">Cancelar</button>
            </div>
            
            <div id="pinMessage" class="message hidden"></div>
        </div>
    </div>

    <!-- Modal para Admin -->
    <div id="adminModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Acceso Administrador</h2>
                <button class="close-btn" id="closeAdminModal">&times;</button>
            </div>
            
            <div class="form-group">
                <label for="adminPassword">Contraseña:</label>
                <input 
                    type="password" 
                    id="adminPassword" 
                    placeholder="Ingrese contraseña" 
                    autocomplete="off"
                >
            </div>
            
            <div class="modal-actions">
                <button id="submitAdminBtn" class="btn btn-primary">Acceder</button>
                <button id="cancelAdminBtn" class="btn btn-secondary">Cancelar</button>
            </div>
            
            <div id="adminMessage" class="message hidden"></div>
        </div>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>
