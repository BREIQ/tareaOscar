# API REST - Documentación de Endpoints

## Base URL

```
http://localhost:8000/api
```

## Autenticación

No se requiere autenticación explícita. Los endpoints están protegidos por validación server-side.

---

## Endpoints

### 1. Registrar Asistencia

**Endpoint:** `POST /api/attendance/register`

**Descripción:** Registra la asistencia de un alumno validando el PIN

**Request:**
```json
{
  "pin": "051234"
}
```

**Response (Éxito):**
```json
{
  "success": true,
  "message": "Asistencia registrada: Juan Pérez – 14:35:20",
  "student_name": "Juan Pérez",
  "time": "14:35:20"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "PIN incorrecto o usuario no encontrado"
}
```

**Validaciones:**
- PIN debe tener exactamente 6 dígitos
- Primeros 2 dígitos = número de alumno
- Últimos 4 dígitos = contraseña del alumno

---

### 2. Validar Contraseña de Admin

**Endpoint:** `POST /api/admin/validate`

**Descripción:** Valida la contraseña de acceso al panel administrativo

**Request:**
```json
{
  "password": "Oscar9234"
}
```

**Response (Éxito):**
```json
{
  "success": true,
  "message": "Acceso permitido"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Contraseña incorrecta"
}
```

**Status Code:** 401 (Unauthorized) si falla

---

## Gestión de Alumnos

### 3. Obtener Todos los Alumnos

**Endpoint:** `GET /api/admin/students`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "_id": "ObjectId(...)",
      "student_number": "05",
      "password": "1234",
      "name": "Juan Pérez"
    },
    {
      "_id": "ObjectId(...)",
      "student_number": "01",
      "password": "1111",
      "name": "Carlos García"
    }
  ]
}
```

---

### 4. Crear Nuevo Alumno

**Endpoint:** `POST /api/admin/students`

**Request:**
```json
{
  "name": "María González",
  "student_number": "06",
  "password": "5678"
}
```

**Response (Éxito):**
```json
{
  "success": true,
  "message": "Alumno creado exitosamente",
  "data": {
    "_id": "ObjectId(...)",
    "name": "María González",
    "student_number": "06",
    "password": "5678"
  }
}
```

**Validaciones:**
- Nombre: No puede estar vacío
- Número de alumno: Exactamente 2 dígitos (00-99)
- Contraseña: Exactamente 4 dígitos (0000-9999)
- El número de alumno no puede existir ya

---

### 5. Actualizar Alumno

**Endpoint:** `PUT /api/admin/students/:student_number`

**Ejemplo:** `PUT /api/admin/students/05`

**Request:**
```json
{
  "name": "Juan Pérez García",
  "password": "9999"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Alumno actualizado exitosamente",
  "data": {
    "student_number": "05",
    "name": "Juan Pérez García",
    "password": "9999"
  }
}
```

**Notas:**
- Al menos un campo (nombre o contraseña) es requerido
- Contraseña debe tener 4 dígitos si se proporciona

---

### 6. Eliminar Alumno

**Endpoint:** `DELETE /api/admin/students/:student_number`

**Ejemplo:** `DELETE /api/admin/students/05`

**Response (Éxito):**
```json
{
  "success": true,
  "message": "Alumno eliminado exitosamente"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Alumno no encontrado"
}
```

**Status Code:** 200 (OK) o 404 (Not Found)

---

## Consulta de Asistencias

### 7. Obtener Todas las Asistencias

**Endpoint:** `GET /api/admin/attendance`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "_id": "ObjectId(...)",
      "student_number": "05",
      "student_name": "Juan Pérez",
      "timestamp": "2025-12-09 14:35:20"
    },
    {
      "_id": "ObjectId(...)",
      "student_number": "01",
      "student_name": "Carlos García",
      "timestamp": "2025-12-09 14:30:15"
    }
  ]
}
```

---

### 8. Obtener Asistencias por Día

**Endpoint:** `GET /api/admin/attendance?type=day&date=2025-12-09`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "_id": "ObjectId(...)",
      "student_number": "05",
      "student_name": "Juan Pérez",
      "timestamp": "2025-12-09 14:35:20"
    }
  ],
  "filter": "Día: 2025-12-09"
}
```

---

### 9. Obtener Asistencias por Mes

**Endpoint:** `GET /api/admin/attendance?type=month&year=2025&month=12`

**Parámetros:**
- `year`: Año en formato YYYY (ej: 2025)
- `month`: Mes en formato MM (ej: 12)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "_id": "ObjectId(...)",
      "student_number": "05",
      "student_name": "Juan Pérez",
      "timestamp": "2025-12-09 14:35:20"
    },
    {
      "_id": "ObjectId(...)",
      "student_number": "01",
      "student_name": "Carlos García",
      "timestamp": "2025-12-08 09:15:45"
    }
  ],
  "filter": "Mes: 2025-12"
}
```

---

### 10. Obtener Asistencias por Año

**Endpoint:** `GET /api/admin/attendance?type=year&year=2025`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "_id": "ObjectId(...)",
      "student_number": "05",
      "student_name": "Juan Pérez",
      "timestamp": "2025-12-09 14:35:20"
    },
    {
      "_id": "ObjectId(...)",
      "student_number": "05",
      "student_name": "Juan Pérez",
      "timestamp": "2025-12-08 10:20:30"
    }
  ],
  "filter": "Año: 2025"
}
```

---

## Códigos de Estado HTTP

| Código | Significado |
|--------|------------|
| 200 | OK - Operación exitosa |
| 201 | Created - Recurso creado |
| 400 | Bad Request - Error en los datos |
| 401 | Unauthorized - Autenticación fallida |
| 404 | Not Found - Recurso no encontrado |
| 500 | Server Error - Error del servidor |

---

## Ejemplos de Uso con cURL

### Registrar Asistencia
```bash
curl -X POST http://localhost:8000/api/attendance/register \
  -H "Content-Type: application/json" \
  -d '{"pin":"051234"}'
```

### Validar Admin
```bash
curl -X POST http://localhost:8000/api/admin/validate \
  -H "Content-Type: application/json" \
  -d '{"password":"Oscar9234"}'
```

### Crear Alumno
```bash
curl -X POST http://localhost:8000/api/admin/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Juan Pérez","student_number":"05","password":"1234"}'
```

### Obtener Alumnos
```bash
curl http://localhost:8000/api/admin/students
```

### Obtener Asistencias por Día
```bash
curl "http://localhost:8000/api/admin/attendance?type=day&date=2025-12-09"
```

---

## Ejemplos de Uso con JavaScript (Fetch API)

### Registrar Asistencia
```javascript
fetch('/api/attendance/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    pin: '051234'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Crear Alumno
```javascript
fetch('/api/admin/students', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: 'Juan Pérez',
    student_number: '05',
    password: '1234'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Obtener Asistencias por Mes
```javascript
const year = 2025;
const month = 12;

fetch(`/api/admin/attendance?type=month&year=${year}&month=${month}`)
  .then(response => response.json())
  .then(data => console.log(data));
```

---

## Notas Importantes

1. **Todos los endpoints retornan JSON**
2. **Las fechas se retornan en formato ISO 8601**: `YYYY-MM-DD HH:MM:SS`
3. **Los IDs de MongoDB se retornan como strings**
4. **Las contraseñas se almacenan en texto plano** (en producción usar hash)
5. **El servidor ajusta automáticamente la zona horaria**

---

**Última actualización**: 9 de diciembre de 2025
