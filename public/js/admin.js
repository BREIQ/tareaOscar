// Elementos del DOM
const logoutBtn = document.getElementById('logoutBtn');
const tabBtns = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');
const addStudentBtn = document.getElementById('addStudentBtn');
const studentFormSection = document.getElementById('studentFormSection');
const studentForm = document.getElementById('studentForm');
const cancelStudentBtn = document.getElementById('cancelStudentBtn');
const studentTableBody = document.getElementById('studentTableBody');
const filterType = document.getElementById('filterType');
const filterOptions = document.getElementById('filterOptions');
const attendanceTableBody = document.getElementById('attendanceTableBody');
const editStudentModal = document.getElementById('editStudentModal');
const editStudentForm = document.getElementById('editStudentForm');
const closeEditModal = document.getElementById('closeEditModal');
const cancelEditBtn = document.getElementById('cancelEditBtn');

let editingStudent = null;

// ===== TAB NAVIGATION =====

tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const tabName = btn.getAttribute('data-tab');
        
        tabBtns.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));
        
        btn.classList.add('active');
        document.getElementById(tabName).classList.add('active');
        
        // Si es la pestaña de asistencias, cargar datos
        if (tabName === 'attendance') {
            loadAttendance();
        }
    });
});

// ===== GESTIÓN DE ALUMNOS =====

addStudentBtn.addEventListener('click', () => {
    studentForm.reset();
    studentFormSection.classList.remove('hidden');
    document.getElementById('formTitle').textContent = 'Crear Nuevo Alumno';
    studentForm.onsubmit = createStudent;
    editingStudent = null;
});

cancelStudentBtn.addEventListener('click', () => {
    studentFormSection.classList.add('hidden');
});

studentForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (editingStudent) {
        await updateStudent();
    } else {
        await createStudent();
    }
});

async function createStudent() {
    const name = document.getElementById('studentName').value;
    const student_number = document.getElementById('studentNumber').value;
    const password = document.getElementById('studentPassword').value;
    
    if (!name || !student_number || !password) {
        showMessage('studentFormMessage', 'Completa todos los campos', 'error');
        return false;
    }
    
    try {
        const response = await fetch('/api/admin/students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, student_number, password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('studentFormMessage', data.message, 'success');
            studentForm.reset();
            setTimeout(() => {
                studentFormSection.classList.add('hidden');
                loadStudents();
            }, 1500);
        } else {
            showMessage('studentFormMessage', data.message, 'error');
        }
    } catch (error) {
        showMessage('studentFormMessage', 'Error al crear alumno', 'error');
    }
    
    return false;
}

async function updateStudent() {
    const name = document.getElementById('studentName').value;
    const password = document.getElementById('studentPassword').value;
    const student_number = editingStudent.student_number;
    
    if (!name) {
        showMessage('studentFormMessage', 'El nombre es requerido', 'error');
        return;
    }
    
    const updateData = { name };
    if (password) {
        updateData.password = password;
    }
    
    try {
        const response = await fetch(`/api/admin/students/${student_number}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updateData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('editMessage', data.message, 'success');
            setTimeout(() => {
                editStudentModal.classList.add('hidden');
                loadStudents();
            }, 1500);
        } else {
            showMessage('editMessage', data.message, 'error');
        }
    } catch (error) {
        showMessage('editMessage', 'Error al actualizar alumno', 'error');
    }
}

async function editStudent(student_number) {
    try {
        const response = await fetch('/api/admin/students');
        const data = await response.json();
        
        const student = data.data.find(s => s.student_number === student_number);
        if (student) {
            editingStudent = student;
            document.getElementById('editStudentNumber').value = student.student_number;
            document.getElementById('editStudentName').value = student.name;
            document.getElementById('editStudentPassword').value = '';
            document.getElementById('editMessage').classList.add('hidden');
            editStudentModal.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error cargando alumno para editar:', error);
    }
}

async function deleteStudent(student_number) {
    if (!confirm('¿Deseas eliminar este alumno?')) return;
    
    try {
        const response = await fetch(`/api/admin/students/${student_number}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadStudents();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al eliminar alumno');
    }
}

async function loadStudents() {
    try {
        const response = await fetch('/api/admin/students');
        const data = await response.json();
        
        if (data.success && data.data) {
            studentTableBody.innerHTML = '';
            
            if (data.data.length === 0) {
                studentTableBody.innerHTML = '<tr><td colspan="4" class="text-center">No hay alumnos registrados</td></tr>';
                return;
            }
            
            data.data.forEach(student => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.student_number}</td>
                    <td>${student.name}</td>
                    <td>${student.password}</td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-primary btn-sm" onclick="editStudent('${student.student_number}')">Editar</button>
                            <button class="btn btn-secondary btn-sm" onclick="deleteStudent('${student.student_number}')">Eliminar</button>
                        </div>
                    </td>
                `;
                studentTableBody.appendChild(row);
            });
        }
    } catch (error) {
        studentTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Error al cargar alumnos</td></tr>';
    }
}

// ===== CONSULTA DE ASISTENCIAS =====

filterType.addEventListener('change', () => {
    const selectedType = filterType.value;
    
    document.querySelectorAll('.filter-hidden').forEach(el => {
        el.classList.remove('show');
    });
    
    if (selectedType === 'all') {
        loadAttendance();
    } else {
        document.getElementById(selectedType + 'Filter').classList.add('show');
    }
});

async function filterAttendance() {
    const type = filterType.value;
    
    if (type === 'all') {
        loadAttendance();
    } else if (type === 'day') {
        const date = document.getElementById('filterDate').value;
        if (!date) {
            showMessage('attendanceMessage', 'Selecciona una fecha', 'error');
            return;
        }
        await loadAttendanceByDay(date);
    } else if (type === 'month') {
        const month = document.getElementById('filterMonth').value;
        if (!month) {
            showMessage('attendanceMessage', 'Selecciona un mes', 'error');
            return;
        }
        const [year, monthNum] = month.split('-');
        await loadAttendanceByMonth(parseInt(year), parseInt(monthNum));
    } else if (type === 'year') {
        const year = document.getElementById('filterYear').value;
        if (!year) {
            showMessage('attendanceMessage', 'Ingresa un año', 'error');
            return;
        }
        await loadAttendanceByYear(parseInt(year));
    }
}

async function loadAttendance() {
    try {
        const response = await fetch('/api/admin/attendance?type=all');
        const data = await response.json();
        renderAttendanceTable(data.data);
    } catch (error) {
        attendanceTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Error al cargar asistencias</td></tr>';
    }
}

async function loadAttendanceByDay(date) {
    try {
        const response = await fetch(`/api/admin/attendance?type=day&date=${date}`);
        const data = await response.json();
        renderAttendanceTable(data.data, data.filter);
    } catch (error) {
        attendanceTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Error al cargar asistencias</td></tr>';
    }
}

async function loadAttendanceByMonth(year, month) {
    try {
        const response = await fetch(`/api/admin/attendance?type=month&year=${year}&month=${month}`);
        const data = await response.json();
        renderAttendanceTable(data.data, data.filter);
    } catch (error) {
        attendanceTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Error al cargar asistencias</td></tr>';
    }
}

async function loadAttendanceByYear(year) {
    try {
        const response = await fetch(`/api/admin/attendance?type=year&year=${year}`);
        const data = await response.json();
        renderAttendanceTable(data.data, data.filter);
    } catch (error) {
        attendanceTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Error al cargar asistencias</td></tr>';
    }
}

function renderAttendanceTable(records, filter) {
    attendanceTableBody.innerHTML = '';
    
    if (!records || records.length === 0) {
        attendanceTableBody.innerHTML = '<tr><td colspan="3" class="text-center">No hay registros</td></tr>';
        return;
    }
    
    records.forEach(record => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${record.student_number}</td>
            <td>${record.student_name}</td>
            <td>${record.timestamp}</td>
        `;
        attendanceTableBody.appendChild(row);
    });
    
    if (filter) {
        showMessage('attendanceMessage', `Mostrando registros: ${filter}`, 'info');
    }
}

// ===== MODALES =====

closeEditModal.addEventListener('click', () => {
    editStudentModal.classList.add('hidden');
});

cancelEditBtn.addEventListener('click', () => {
    editStudentModal.classList.add('hidden');
});

editStudentForm.addEventListener('submit', (e) => {
    e.preventDefault();
    updateStudent();
});

editStudentModal.addEventListener('click', (e) => {
    if (e.target === editStudentModal) {
        editStudentModal.classList.add('hidden');
    }
});

// ===== LOGOUT =====

logoutBtn.addEventListener('click', () => {
    if (confirm('¿Deseas cerrar sesión?')) {
        window.location.href = '/';
    }
});

// ===== FUNCIONES AUXILIARES =====

function showMessage(elementId, message, type) {
    const element = document.getElementById(elementId);
    element.textContent = message;
    element.className = `message ${type}`;
    element.classList.remove('hidden');
}

// Cargar alumnos y asistencias al iniciar
window.addEventListener('load', () => {
    loadStudents();
    loadAttendance();
});
