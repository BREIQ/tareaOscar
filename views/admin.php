<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Panel de Administración</h1>
            <button id="logoutBtn" class="btn btn-secondary">Salir</button>
        </div>

        <div class="admin-tabs">
            <button class="tab-btn active" data-tab="students">Gestión de Alumnos</button>
            <button class="tab-btn" data-tab="attendance">Consulta de Asistencias</button>
        </div>

        <!-- TAB: GESTIÓN DE ALUMNOS -->
        <div id="students" class="tab-content active">
            <div class="section-header">
                <h2>Gestión de Alumnos</h2>
                <button id="addStudentBtn" class="btn btn-primary">+ Nuevo Alumno</button>
            </div>

            <div class="form-section hidden" id="studentFormSection">
                <h3 id="formTitle">Crear Nuevo Alumno</h3>
                <form id="studentForm">
                    <div class="form-group">
                        <label for="studentName">Nombre:</label>
                        <input type="text" id="studentName" placeholder="Nombre completo" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="studentNumber">Número (2 dígitos):</label>
                            <input 
                                type="text" 
                                id="studentNumber" 
                                placeholder="00" 
                                pattern="\d{2}" 
                                maxlength="2"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="studentPassword">Contraseña (4 dígitos):</label>
                            <input 
                                type="text" 
                                id="studentPassword" 
                                placeholder="0000" 
                                pattern="\d{4}" 
                                maxlength="4"
                                required
                            >
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" id="cancelStudentBtn" class="btn btn-secondary">Cancelar</button>
                    </div>
                </form>
                <div id="studentFormMessage" class="message hidden"></div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nombre</th>
                        <th>Contraseña</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <tr>
                        <td colspan="4" class="text-center">Cargando alumnos...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TAB: CONSULTA DE ASISTENCIAS -->
        <div id="attendance" class="tab-content">
            <div class="section-header">
                <h2>Consulta de Asistencias</h2>
            </div>

            <div class="filter-section">
                <div class="filter-group">
                    <label for="filterType">Filtrar por:</label>
                    <select id="filterType">
                        <option value="all">Todas</option>
                        <option value="day">Día</option>
                        <option value="month">Mes</option>
                        <option value="year">Año</option>
                    </select>
                </div>

                <div id="filterOptions" class="filter-options hidden">
                    <div id="dayFilter" class="filter-hidden">
                        <input type="date" id="filterDate">
                        <button class="btn btn-primary" onclick="filterAttendance()">Buscar</button>
                    </div>

                    <div id="monthFilter" class="filter-hidden">
                        <input type="month" id="filterMonth">
                        <button class="btn btn-primary" onclick="filterAttendance()">Buscar</button>
                    </div>

                    <div id="yearFilter" class="filter-hidden">
                        <input type="number" id="filterYear" placeholder="YYYY" min="2000" max="2099">
                        <button class="btn btn-primary" onclick="filterAttendance()">Buscar</button>
                    </div>
                </div>
            </div>

            <div id="attendanceMessage" class="message hidden"></div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nombre</th>
                        <th>Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody">
                    <tr>
                        <td colspan="3" class="text-center">Cargando asistencias...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Editar Alumno -->
    <div id="editStudentModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Alumno</h2>
                <button class="close-btn" id="closeEditModal">&times;</button>
            </div>
            
            <form id="editStudentForm">
                <input type="hidden" id="editStudentNumber">
                
                <div class="form-group">
                    <label for="editStudentName">Nombre:</label>
                    <input type="text" id="editStudentName" required>
                </div>
                <div class="form-group">
                    <label for="editStudentPassword">Contraseña (4 dígitos):</label>
                    <input 
                        type="text" 
                        id="editStudentPassword" 
                        placeholder="0000" 
                        pattern="\d{4}" 
                        maxlength="4"
                    >
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="button" id="cancelEditBtn" class="btn btn-secondary">Cancelar</button>
                </div>
            </form>
            <div id="editMessage" class="message hidden"></div>
        </div>
    </div>

    <script src="/js/admin.js"></script>
</body>
</html>
