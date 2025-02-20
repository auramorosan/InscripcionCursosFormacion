# Proyecto de Inscripción a Cursos de Formación

## Descripción
Este es un sistema en PHP con acceso a una base de datos SQL que permite a profesores inscribirse en cursos de formación y a administradores gestionar dichos cursos.

### Características Principales:
- **Login:** Acceso para profesores y administradores mediante DNI y usuario.
- **Logout:** Cierre de sesión seguro.
- **Profesores:**
  - Registro a través de un formulario.
  - Inscripción en cursos abiertos (solo una vez por curso).
  - Generación de un PDF de inscripción enviado al correo vía Axigen WebMail.
- **Administradores:**
  - Activación y desactivación de cursos.
  - Creación y eliminación de cursos.
  - Baremación de solicitantes en cursos cerrados según puntuación.

## Instalación
1. Clona el repositorio del proyecto:
   ```sh
   git clone <URL_DEL_REPOSITORIO>
   ```
2. Configura la base de datos SQL:
   - Crea una base de datos en MySQL o PostgreSQL.
   - Importa el script SQL proporcionado en el proyecto.
3. Asegúrate de incluir las siguientes carpetas en el directorio del proyecto:
   - **FPDF186**: Descárgala desde GitHub e inclúyela en el proyecto.
   - **Carpeta de PDFs**: Crea una carpeta para almacenar los PDF generados.
   - **PhpMailer**: Descarga e incluye la carpeta de PhpMailer desde GitHub.
4. Verifica que las rutas de los archivos estén correctamente configuradas.

## Uso
### Acceso a la aplicación
1. Abre la página principal y accede con tu usuario y DNI.
2. Si eres profesor y no estás registrado, usa el formulario de registro.

### Como Profesor
- Inicia sesión y revisa los cursos disponibles.
- Inscríbete en un curso (solo una vez por cada curso).
- Recibirás un PDF de confirmación vía correo electrónico.

### Como Administrador
- Inicia sesión con credenciales de administrador.
- Activa o desactiva cursos según disponibilidad.
- Añade o elimina cursos según necesidad.
- Realiza la baremación de profesores inscritos en cursos cerrados.

## Requisitos
- Servidor web con soporte para PHP (Apache, Nginx, etc.).
- Base de datos MySQL o PostgreSQL.
- PHP con extensión para manejo de bases de datos.
- Biblioteca **FPDF** para generación de PDFs.
- **PhpMailer** para envío de correos electrónicos.

## Consideraciones Finales
- Asegúrate de configurar correctamente las credenciales de la base de datos.
- Verifica la correcta configuración del servidor de correos para el envío de PDFs.
- Revisa las rutas de las librerías externas antes de ejecutar el proyecto.



