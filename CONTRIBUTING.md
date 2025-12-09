# Contribuir al Proyecto

Gracias por tu interés en contribuir al Sistema de Asistencia. Este documento proporciona pautas para contribuir.

## Cómo Contribuir

### 1. Fork y Clone
```bash
git clone https://github.com/tu-usuario/tareaOscar.git
cd tareaOscar
```

### 2. Crear rama feature
```bash
git checkout -b feature/nombre-feature
```

### 3. Realizar cambios
- Mantener el código limpio y comentado
- Seguir la estructura actual del proyecto
- Usar nombres descriptivos para variables y funciones

### 4. Commit
```bash
git add .
git commit -m "feat: descripción clara de los cambios"
```

### 5. Push y Pull Request
```bash
git push origin feature/nombre-feature
```

## Pautas de Código

### PHP
- Usar namespace `App\`
- Nombrar clases con PascalCase
- Nombrar métodos con camelCase
- Agregar docblocks a métodos públicos
- Validar siempre inputs

Ejemplo:
```php
namespace App\Models;

/**
 * Clase para gestionar estudiantes
 */
class Student
{
    /**
     * Buscar un estudiante por número
     *
     * @param string $student_number Número del alumno (2 dígitos)
     * @return array|null Datos del alumno o null
     */
    public function findByNumber(string $student_number): ?array
    {
        // Implementación
    }
}
```

### JavaScript
- Usar `const` y `let` (nunca `var`)
- Nombrar variables con camelCase
- Agregar comentarios explicativos
- Usar async/await en lugar de callbacks

Ejemplo:
```javascript
async function loadStudents() {
    try {
        const response = await fetch('/api/admin/students');
        const data = await response.json();
        
        if (data.success) {
            renderTable(data.data);
        }
    } catch (error) {
        showError('Error al cargar alumnos');
    }
}
```

### CSS
- Usar variantes CSS (variables)
- Mobile-first approach
- Comentar secciones principales
- Usar nombres descriptivos para clases

Ejemplo:
```css
/* ===== SECTION NAME ===== */

.element {
    display: flex;
    gap: var(--spacing);
    background: linear-gradient(135deg, var(--primary), var(--secondary));
}
```

## Reportar Bugs

1. Verificar que el bug no está reportado
2. Incluir:
   - Descripción clara del problema
   - Pasos para reproducir
   - Resultado esperado vs actual
   - Versión PHP, MongoDB
   - Sistema operativo y navegador

## Sugerencias de Mejoras

- Abrir issue con etiqueta `enhancement`
- Describir la funcionalidad deseada
- Explicar por qué sería útil
- Proporcionar ejemplos si es posible

## Revisión

- Verificar que el código pasa validaciones
- Revisar cambios en base de datos
- Comprobar compatibilidad
- Actualizar documentación si es necesario

## Convenciones de Commit

```
feat: Agregar nueva funcionalidad
fix: Corregir bug
docs: Actualizar documentación
style: Cambios de formato (sin lógica)
refactor: Restructurar código
perf: Mejora de performance
test: Agregar o actualizar tests
chore: Tareas de mantenimiento
```

## Requerimientos

- PHP 8.0+
- MongoDB 4.4+
- Código limpio y documentado
- Tests si es posible
- Documentación actualizada

## Código de Conducta

- Ser respetuoso
- Aceptar críticas constructivas
- Enfocarse en lo mejor para el proyecto
- No spam o contenido ofensivo

## Licencia

Al contribuir, aceptas que tu código sea usado bajo la licencia del proyecto.

---

¡Gracias por contribuir!
