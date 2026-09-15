# Qué falta implementar en la aplicación

## 1. Resumen ejecutivo
La aplicación tiene una base funcional: CRUD de libros, socios y préstamos, interfaz con Bootstrap y conexión a base de datos. Sin embargo, todavía hay varios puntos críticos que faltan implementar o corregir para que funcione de manera segura, estable y realista para uso con usuarios reales.

La prioridad más alta no es añadir más vistas o contenido visual, sino cerrar los puntos de seguridad, autenticación, permisos y flujo de negocio.

---

## 2. Lo que ya existe
Se observan los siguientes elementos ya construidos:

- Login básico en `login.php`
- Protección simple de sesión en `includes/seguridad.php`
- CRUD para libros en `controllers/LibroController.php` y `models/Libro.php`
- CRUD para socios en `controllers/SocioController.php` y `models/socio.php`
- CRUD para préstamos en `controllers/PrestamoController.php` y `models/prestamo.php`
- Vistas de formulario y listado para cada módulo
- Base de datos con tablas `libros`, `socios`, `prestamos` y `usuarios`

---

## 3. Lo que falta implementar de forma crítica

### 3.1. Autenticación real de usuarios
El sistema actual valida contra usuario fijo:

- usuario: `thony`
- password: `1234`

Esto está en `login.php`.

Falta:
- consultar usuarios reales desde la tabla `usuarios`
- usar `password_hash()` y `password_verify()`
- manejar intentos fallidos
- definir roles: admin, bibliotecario, usuario
- mantener sesión autenticada con información real del usuario

---

### 3.2. Permisos por usuario y por recurso
La app solo comprueba si existe sesión, pero no valida qué puede hacer cada usuario.

Falta:
- roles por usuario
- autorización antes de crear, editar y eliminar
- validación de recursos por ID
- impedir edit/delete de registros ajenos
- controlar acceso directo por URL o manipulación de parámetros

Esto es especialmente importante en `index.php` y en todos los controladores.

---

### 3.3. CRUD con lógica corregida
Hay varios fallos concretos en la lógica de los controladores:

- En `SocioController.php`, la edición usa `Socio::crear(...)` en lugar de actualizar
- En `PrestamoController.php`, la edición usa `Prestamo::crear(...)`
- El modelo `Prestamo` no tiene un método `actualizar()` real
- Hay variables inconsistentes como `$socio` / `$socios`, `$prestamo` / `$prestamos`
- Algunos formularios se llaman con nombres de acción que no están bien alineados con la lógica de negocio

Falta:
- diseñar una capa consistente de CRUD
- corregir cada acción de edición
- revisar que cada flujo tenga validación y redirección correctas

---

### 3.4. Validación de negocio real
La app hace validaciones mínimas, pero no reglas de negocio reales.

Falta implementar:
- no duplicar cédulas de socios
- validar textos vacíos o con longitud mínima
- validar teléfono y documento
- validar fechas de préstamo
- controlar disponibilidad del libro
- impedir préstamo de un libro ya prestado
- impedir fechas futuras o inválidas
- controlar errores cuando el libro o socio no existen

La columna `disponible` existe en la tabla `libros`, pero no está aplicada en la lógica.

---

### 3.5. Protección CSRF
No hay token CSRF en los formularios.

Esto afecta:
- crear y editar libros
- crear y editar socios
- crear y editar préstamos
- cualquier acción que use `POST`

Falta:
- generar token por sesión
- enviarlo en cada formulario
- validarlo antes de ejecutar la acción

---

### 3.6. Seguridad en uploads de portadas
El método `subirPortada()` en `LibroController.php` solo valida la extensión.

Falta:
- validar tipo MIME real
- validar contenido real con `getimagesize()`
- limitar tamaño de archivo
- rechazar archivos maliciosos
- cambiar nombre de archivo por un valor seguro
- proteger la carpeta de uploads
- revisar permisos del servidor

Esto no es suficiente para producción.

---

### 3.7. Sesiones seguras
La sesión actual es básica.

Falta:
- `session_regenerate_id(true)` al iniciar sesión
- cookies con `HttpOnly`, `Secure`, `SameSite`
- expiración de sesión por inactividad
- destrucción completa de sesión al cerrar
- prevención de fijación de sesión

Se ve esto en `includes/seguridad.php` y `logout.php`.

---

### 3.8. Manejo seguro de errores
En `config/conexion.php` se muestra el error interno con `PDOException`.

Falta:
- logs del servidor
- mensajes genéricos para usuarios finales
- no revelar nombres de BD, rutas, estructuras internas o detalles del entorno

---

## 4. Lo que falta implementar en la experiencia del usuario
Además de la seguridad, la aplicación aún necesita mejorar varios puntos funcionales.

### 4.1. Búsqueda y filtros
Falta:
- buscar libro por título o autor
- buscar socio por nombre o cédula
- filtrar préstamos por fecha o socio
- filtrar por disponibilidad

### 4.2. Dashboard o resumen
Falta:
- cantidad total de libros
- cantidad de socios
- cantidad de préstamos activos
- estadísticas rápidas

### 4.3. Confirmación antes de eliminar
Falta:
- confirmación con modal o confirm() antes de borrar
- evitar eliminación accidental

### 4.4. Mensajes claros de éxito y error
Falta:
- toasts o alertas más estructuradas
- mensajes de confirmación para crear, editar y eliminar
- mensajes más específicos por validación

### 4.5. Mejoras de navegabilidad
Falta:
- volver desde detail a listado
- mejor control de estados de paginación
- navegación más intuitiva

---

## 5. Qué falta implementar de calidad y pruebas
La aplicación no tiene una base sólida de pruebas.

Falta:
- pruebas funcionales del CRUD
- pruebas de validación de formulario
- pruebas de autenticación
- pruebas de permisos
- pruebas de seguridad básica
- pruebas de regresión

Este punto es importante porque sin pruebas, se corre el riesgo de romper la app mientras se corrige seguridad.

---

## 6. Prioridades reales de implementación

### Prioridad 1: Seguridad crítica
- login real con usuarios y hash
- permisos y roles
- sesión segura
- CSRF
- validación de entradas

### Prioridad 2: Corrección funcional
- CRUD correcto en socios y préstamos
- validación de negocio
- manejo correcto de IDs
- evitar errores de flujo

### Prioridad 3: Seguridad de archivos
- uploads seguros
- validación MIME y tamaño
- almacenamiento seguro

### Prioridad 4: Calidad y entrega
- pruebas de regresión
- mensajes y UX
- documentación final
- revisión general antes de dar por terminado

---

## 7. Conclusión
La aplicación ya tiene una base funcional, pero le faltan elementos esenciales para ser una app completa, segura y confiable. Los puntos más importantes a resolver son:

1. autenticación real
2. permisos por rol
3. CRUD corregido y validado
4. CSRF
5. sesiones y seguridad del backend
6. control de uploads
7. pruebas funcionales y de regresión

Si se resuelven estos puntos en orden, la aplicación tendrá una estructura mucho más sólida y estará mucho más cerca de estar lista para uso real.
