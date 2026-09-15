# Planificación de sprints para los próximos 2 meses

## 1. Objetivo general
Completar la aplicación de gestión de biblioteca con una base funcional estable, segura y validada, corrigiendo los principales hallazgos detectados en la batería de pruebas y dejando el proyecto en una versión lista para uso real o para presentación final.

## 2. Resultado esperado al final de 2 meses
La aplicación deberá cumplir con estas condiciones:
- Login seguro con usuarios reales y contraseñas hasheadas
- Control de sesiones y permisos por rol
- Protección CSRF en formularios críticos
- Validaciones de entradas y manejo de errores consistente
- CRUD completo y estable para libros, socios y préstamos
- Subida de archivos segura para portadas
- Validación funcional de todas las pantallas
- Informe de pruebas y checklist de calidad final

## 3. Principales hallazgos que condicionan la planificación
Los riesgos más importantes detectados son:
1. Credenciales hardcodeadas en login
2. Usuario MySQL root con contraseña vacía
3. Falta de permisos por usuario/rol y por recurso
4. Ausencia de protección CSRF
5. Validación débil de archivos subidos
6. Sesiones no robustas
7. Exposición de errores internos
8. Validaciones funcionales incompletas en algunos flujos

Estos deben resolverse en este orden para evitar re-trabajo y pérdida de tiempo.

---

## 4. Estrategia general por sprints
Se trabajará con 8 sprints de 2 semanas cada uno, totalizando 16 semanas.

### Sprint 1: Diagnóstico final + base de seguridad
Duración: semanas 1-2

#### Objetivos
- Consolidar el backlog final
- Revisar arquitectura actual y definir la estructura segura
- Corregir la base de autenticación y sesión
- Definir roles y estructura de permisos

#### Tareas
- Crear esquema de usuarios con tabla `usuarios`
- Implementar `password_hash()` y `password_verify()`
- Corregir login actual en `login.php`
- Configurar sesión segura con `session_regenerate_id(true)`
- Definir roles: admin, bibliotecario, usuario (si aplica)
- Documentar rutas y permisos por módulo
- Preparar base de datos para usuarios y pruebas

#### Criterios de aceptación
- El login ya no usa credenciales fijas
- Un usuario real puede iniciar sesión
- La sesión se renueva al ingresar
- Las rutas protegidas requieren sesión activa

#### Riesgos
- No definir roles al principio provoca rework posterior
- Cambios en login pueden afectar flujo de trabajo de la app

---

### Sprint 2: Seguridad de acceso y autorización
Duración: semanas 3-4

#### Objetivos
- Implementar controles de acceso por rol y por recurso
- Bloquear acceso directo a acciones sensibles
- Redefinir la lógica de flujo de index y controladores

#### Tareas
- Crear helpers de autorización
- Validar permisos antes de editar/eliminar libros, socios y préstamos
- Rechazar IDs inválidos o inexistentes
- Redirigir con mensajes claros cuando no hay permisos
- Añadir control de acceso por módulo
- Revisar endpoints involucrados en `index.php` y controladores

#### Criterios de aceptación
- Un usuario sin permisos no puede editar, crear o borrar recursos ajenos
- Las acciones no autorizadas devuelven 403 o redirección segura
- No existe acceso directo por manipulación de parámetros GET/POST

#### Riesgos
- Algunas vistas asumen permisos implícitos
- Debe revisarse cuidadosamente cada acción de CRUD

---

### Sprint 3: Protección CSRF + formularios seguros
Duración: semanas 5-6

#### Objetivos
- Proteger todas las acciones con POST
- Añadir tokens CSRF al flujo de formularios
- Revisar y corregir formularios de creación y edición

#### Tareas
- Generar token CSRF al iniciar sesión
- Insertar token oculto en cada formulario
- Validar token antes de procesar POST
- Reforzar validaciones del lado del servidor
- Revisar formularios de libros, socios y préstamos

#### Criterios de aceptación
- Cualquier petición POST sin token válido es rechazada
- El formulario de login y los CRUDs funcionan con la protección activa
- No se puede forzar acción desde un origen externo

#### Riesgos
- Algunos formularios están repartidos en varias vistas y requieren coordinación
- Fallos en validación pueden bloquear uso normal si no se prueba bien

---

### Sprint 4: Validación de entradas y manejo de errores
Duración: semanas 7-8

#### Objetivos
- Mejorar la integridad de los datos
- Centralizar validaciones de negocio
- Utilizar mensajes de error claros y seguros

#### Tareas
- Validar campos obligatorios con reglas concretas
- Rechazar valores nulos, vacíos o fuera de rango
- Validar formatos de fechas, cédulas, teléfonos y números
- Corregir tratamiento de errores en `config/conexion.php`
- Centralizar mensajes de error y feedback al usuario
- Loggear errores internos sin exponer detalles

#### Criterios de aceptación
- Los formularios no aceptan entradas inválidas
- Los errores se muestran de forma útil y segura
- El sistema no revela detalles internos de la base de datos

#### Riesgos
- Un exceso de validación puede bloquear operaciones válidas
- Debe equilibrarse con el flujo real del negocio

---

### Sprint 5: Seguridad de uploads y archivos
Duración: semanas 9-10

#### Objetivos
- Fortalecer carga de portadas
- Evitar upload de archivos maliciosos
- Dejar el almacenamiento de archivos controlado

#### Tareas
- Validar MIME real con `finfo_file()`
- Usar `getimagesize()` para comprobar que es imagen válida
- Limitar tamaño de archivos
- Renombrar archivos aleatoriamente
- Guardar archivos en una carpeta segura
- Rechazar extensiones no permitidas y doble extensión
- Revisar permisos de la carpeta `uploads/portadas/`

#### Criterios de aceptación
- Solo se aceptan imágenes reales y permitidas
- Archivos no válidos se rechazan antes de guardarse
- La carpeta de uploads no permite ejecución de script

#### Riesgos
- El equipo puede asumir que solo la extensión basta; esto es un riesgo real
- Necesidad de probar varios archivos de prueba

---

### Sprint 6: Continuidad del CRUD y pruebas funcionales
Duración: semanas 11-12

#### Objetivos
- Revalidar libros, socios y préstamos
- Corregir errores de flujo y UX
- Completar pruebas funcionales del sistema

#### Tareas
- Probar CRUD completo para libros
- Probar CRUD completo para socios
- Probar CRUD completo para préstamos
- Verificar listado, detalle, edición y eliminación
- Revisar validaciones de negocio del flujo completo
- Corregir inconsistencias de nombres, redirecciones y mensajes

#### Criterios de aceptación
- Todos los módulos CRUD funcionan sin errores visibles
- La navegación entre pantallas es coherente
- Las acciones de creación/edición/eliminación siguen el flujo correcto

#### Riesgos
- Algunas acciones pueden depender de IDs incorrectos o rutas rotas
- Necesita prueba real con navegador

---

### Sprint 7: Calidad, pruebas y regresiones
Duración: semanas 13-14

#### Objetivos
- Ejecutar batería de pruebas funcionales y de regresión
- Verificar que todas las correcciones no rompen el resto
- Preparar la versión estable del proyecto

#### Tareas
- Ejecutar pruebas de login/registro/edición
- Probar escenarios con entradas inválidas
- Probar listas vacías y casos límite
- Repetir pruebas de seguridad básica
- Revisar flujo completo desde login hasta CRUD
- Completar checklist de regresión

#### Criterios de aceptación
- No se observa regresión funcional en módulos ya corregidos
- La aplicación responde adecuadamente ante entradas inválidas
- El sistema funciona en un flujo de pruebas completo

#### Riesgos
- Cambios de seguridad pueden afectar UX si no se validan bien
- Debe dejarse margen para correcciones de última hora

---

### Sprint 8: Preparación final, documentación y entrega
Duración: semanas 15-16

#### Objetivos
- Dejar proyecto listo para entrega o presentación
- Documentar arquitectura, configuración y seguridad
- Preparar versión final y manual de uso

#### Tareas
- Revisar README/manual del sistema
- Documentar estructura de carpetas
- Documentar configuración de base de datos y variables
- Preparar checklist final de despliegue local
- Realizar pruebas finales de flujo completo
- Preparar informe de resultados y mejoras pendientes
- Definir próximos pasos post-entrega

#### Criterios de aceptación
- Proyecto funcional, documentado y consistente
- El usuario puede ejecutar la app con instrucciones claras
- El estado final está al día con lo probado y corregido

#### Riesgos
- Dejar documentación incompleta compromete entrega final
- Faltan detalles de entorno local

---

## 5. Prioridad de entregas
### Bloque 1: seguridad crítica (obligatorio antes de seguir)
- autenticación segura
- sesiones
- autorización por recurso
- protección CSRF

### Bloque 2: calidad funcional
- validaciones
- CRUD estable
- manejo de errores
- flujo de usuario coherente

### Bloque 3: seguridad de archivos y entorno
- uploads seguros
- configuración de BD y permisos
- hardening final

### Bloque 4: entrega final
- pruebas de regresión
- documentación
- preparación para presentación

---

## 6. Cronograma resumido
| Sprint | Semanas | Enfoque | Entregable principal |
|--------|---------|---------|---------------------|
| 1 | 1-2 | Base de seguridad | Login seguro y sesión protegida |
| 2 | 3-4 | Autorización | Permisos por módulo y recurso |
| 3 | 5-6 | CSRF | Formularios protegidos |
| 4 | 7-8 | Validaciones | Inputs seguros y manejo de errores |
| 5 | 9-10 | Uploads | Archivos seguros |
| 6 | 11-12 | CRUD y pruebas | Módulos funcionales y estables |
| 7 | 13-14 | QA y regresión | Checklist de calidad |
| 8 | 15-16 | Entrega final | Proyecto documentado y validado |

---

## 7. Reglas para mantener el proyecto en tiempo
- No empezar nuevas funcionalidades antes de cerrar la seguridad base
- Cada sprint debe terminar con pruebas de validación específica
- No dejar tareas abiertas al final de sprint sin responsable
- Reunión de revisión cada 10 días para medir avance
- Cada corrección debe ir acompañada de una prueba

---

## 8. Matriz de riesgos del proyecto
| Riesgo | Probabilidad | Impacto | Plan de mitigación |
|--------|--------------|---------|-------------------|
| Login inseguro | Alta | Crítico | Implementar usuario real + hash |
| Permisos insuficientes | Alta | Crítico | Definir roles y validación por recurso |
| CSRF | Alta | Alto | Token en formularios |
| Upload malicioso | Media | Alto | Validar MIME, tamaño y tipo real |
| Error de BD expuesto | Media | Medio | Logs y mensajes genéricos |
| Falta de validación en formularios | Alta | Medio | Validaciones de servidor |
| Pérdida de tiempo por rework | Media | Alto | Seguir prioridad de seguridad antes de funcionalidad |

---

## 9. Plan de trabajo semanal recomendado
### Semana 1-2
- análisis final de requisitos
- login seguro
- sesión
- base de usuarios

### Semana 3-4
- permisos
- protección por rutas
- validación de acciones sensibles

### Semana 5-6
- CSRF
- revisión de formularios
- pruebas de seguridad básica

### Semana 7-8
- validación de entradas
- manejo de errores
- pruebas de regresión

### Semana 9-10
- uploads seguros
- prueba de archivos
- ajustes finales de seguridad

### Semana 11-12
- CRUD completo
- revisión UI/UX
- pruebas funcionales

### Semana 13-14
- QA general
- corrección de defectos
- pruebas integrales

### Semana 15-16
- documentación
- preparación final
- entrega

---

## 10. Recomendación final
La mejor manera de cumplir el proyecto en dos meses es priorizar seguridad y estabilidad antes de añadir mejoras cosméticas. Si se trabaja en este orden, la app no solo estará funcional, sino que tendrá una base sólida para seguir creciendo.

El proyecto puede quedar en un estado muy bueno si se cumple esta progresión y se revisa cada sprint con criterio de calidad.

---

## 11. Entregables por sprint
- Sprint 1: login seguro + sesión
- Sprint 2: permisos y control de acceso
- Sprint 3: CSRF y formularios seguros
- Sprint 4: validación y manejo de errores
- Sprint 5: uploads seguros
- Sprint 6: CRUD completo validado
- Sprint 7: prueba de regresión y QA
- Sprint 8: documentación y entrega final

---

## 12. Cierre
Con este plan, el proyecto queda dividido en entregables claros, medibles y ejecutables en un plazo de dos meses. Si se sigue disciplina y se priorizan los riesgos encontrados desde el inicio, la app tendrá mucha más posibilidad de completarse en tiempo y con mejor calidad.
