# InscripcionCursosFormacion
practica en php que simula una aplicación con acceso a una base de datos SQL que da acceso a profesores a inscribirse en cursos de formación 
Descripcion:
Tiene una pagina principal con el login que permite entrar a la app a profesores y administradores con el dni e usuario y muestra los cursos abiertos. 
Tiene un logout.
Como profesor:
Si no estan registrado, da la opcion de hacerlo a traves de un formulario que guarda los datos en una base de datos SQL.
Una vez registrado y logueado, puedes inscribirte en cursos que estan abiertos. 
Si te has inscrito en un curso(solo se puede hacer una vez por cada curso), se genera un PDF que se manda a tu de correo (se usa Axigen WebMail).
Como admin:
Puedes activar o desactivar los cursos, añadir o eliminar cursos y hace la baremacion de los solicitantes por curso cerrado y segun los puntos que tienen.

Tendras que añadir al proyecto:
-la capeta FPDF186 que te puedes descargar de Git
-una carpeta donde se van a guardar los pdf-s generados
-la carpeta que contiene PhpMailer que tambien enontraras en Git
Fijate bien en las rutas para que el proyecto pueda funcionar bien!!!

