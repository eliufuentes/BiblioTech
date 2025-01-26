BiblioTech - Sistema de Gestión de Biblioteca
BiblioTech es un sistema básico para gestionar libros en una biblioteca. Está desarrollado en PHP utilizando programación orientada a objetos y cuenta con funcionalidades esenciales como agregar, editar, eliminar y buscar libros. También permite cambiar el estado de los libros entre "Disponible" y "Prestado".

Funcionalidades
Agregar, editar y eliminar libros.
Buscar libros por título, autor o categoría.
Cambiar el estado de un libro (Disponible/Prestado) con un botón.
Ver estadísticas de libros (total, disponibles y prestados).
Navegar entre páginas de libros con una paginación dinámica.
Tecnologías Utilizadas
PHP (con programación orientada a objetos).
MySQL para la base de datos.
Bootstrap para la interfaz gráfica.
Requisitos
Para utilizar este proyecto, necesitas:

Un servidor local (como XAMPP o Laragon).
PHP (versión 7.4 o superior).
MySQL.
Cómo Configurarlo
Descarga el proyecto o clónalo con Git:
bash
Copiar
Editar
git clone https://github.com/eliufuentes/BiblioTech.git
Coloca la carpeta del proyecto en el directorio de tu servidor local (por ejemplo, htdocs en XAMPP).
Crea una base de datos llamada bibliotech e importa el archivo SQL con la estructura y datos iniciales.
Configura la conexión a la base de datos en el archivo config/db.php:
php
Copiar
Editar
define('DB_HOST', 'localhost');
define('DB_NAME', 'bibliotech');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
Abre el navegador y accede al sistema:
arduino
Copiar
Editar
http://localhost/BiblioTech
Notas
Este proyecto fue creado con fines académicos para practicar programación orientada a objetos y manejo básico de datos. Es un sistema sencillo que puede ampliarse según las necesidades.