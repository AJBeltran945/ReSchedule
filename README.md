# ReSchedule

ReSchedule es una herramienta web para organizar y gestionar horarios de manera automática. Diseñada especialmente para estudiantes, permite configurar tareas con diferentes niveles de prioridad sin la necesidad de hacerlo manualmente.

La idea surgió al ver a familiares y amigos estudiantes organizando sus horarios manualmente con notas adhesivas o agendas. Con ReSchedule, este proceso se automatiza, ahorrando tiempo y esfuerzo.

## Objetivos

- Organiza tu horario automáticamente
- Personaliza tus tareas
- Notificaciones vía email
- Inicio de sesión
- Soporte para múltiples idiomas

ReSchedule permite la creación y gestión de diferentes tipos de tareas:

- **Tarea Importante**: Tareas con una hora fija (como exámenes o entrevistas).
- **Tarea Pequeña**: Tareas que se pueden asignar dentro de intervalos de horas y se organizan por prioridad manualmente o vinculadas a otras.
- **Tarea Vinculada**: Tareas relacionadas entre sí. Si una tarea tiene una fecha próxima y está vinculada a otra, se prioriza automáticamente.

## Tecnologías utilizadas

- **Laravel**: Backend sólido con autenticación, rutas y lógica de negocio.
- **Tailwind CSS**: Diseño rápido y eficiente sin necesidad de escribir mucho CSS.
- **PostgreSQL**: Base de datos potente, ideal para consultas avanzadas y estructuras de datos complejas.
- **Laravel Livewire**: Interactividad en tiempo real sin necesidad de escribir mucho JavaScript.
- **Vue.js**: Para componentes dinámicos y reactividad avanzada cuando sea necesario.
- **Laravel Queue**: Procesamiento en segundo plano para emails y tareas automatizadas.
- **Cron Jobs**: Automatización de la organización de tareas y recordatorios.

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.

## Diagrama E/R

A continuación, se presentan las tablas que componen la base de datos del proyecto:

### **Usuarios**
| Atributo        | Tipo        | Descripción                       |
|-----------------|-------------|-----------------------------------|
| id              | bigint (PK) | ID único del usuario              |
| name            | string      | Nombre del usuario                |
| email           | string      | Correo electrónico único          |
| password        | string      | Contraseña cifrada                |
| language        | string      | Idioma preferido (‘es’, ‘en’, etc.) |
| created_at      | timestamp   | Fecha de registro                 |
| updated_at      | timestamp   | Última modificación               |

### **Tareas**
| Atributo           | Tipo        | Descripción                             |
|--------------------|-------------|-----------------------------------------|
| id                 | bigint (PK) | ID único de la tarea                    |
| user_id            | bigint (FK) | Usuario que creó la tarea               |
| title              | string      | Título de la tarea                      |
| description        | text        | Detalle de la tarea                     |
| type               | enum        | `importante`, `peque`, `vinculada`      |
| priority           | int         | Nivel de prioridad                      |
| start_date         | datetime    | Fecha/hora de inicio (nullable)         |
| end_date           | datetime    | Fecha/hora de fin (nullable)            |
| related_task_id    | bigint (FK) | ID de otra tarea (nullable)             |
| completed          | boolean     | Si ya fue marcada como terminada        |
| created_at         | timestamp   | Fecha de creación                       |
| updated_at         | timestamp   | Última modificación                     |

### **Notificaciones**
| Atributo       | Tipo        | Descripción                           |
|----------------|-------------|---------------------------------------|
| id             | bigint (PK) | ID único de la notificación           |
| task_id        | bigint (FK) | ID de la tarea asociada               |
| user_id        | bigint (FK) | Usuario destinatario                  |
| message        | string      | Texto de la notificación              |
| sent_at        | timestamp   | Fecha de envío                        |
| is_sent        | boolean     | Si ya fue enviado o no                |

## Ver el diagrama E/R

Aquí está el diagrama E/R de la base de datos:

![Diagrama E/R](diagrams/diagramER.svg)
