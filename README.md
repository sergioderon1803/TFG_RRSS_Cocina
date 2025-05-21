-- MANUAL DE INSTALACIÓN --

REQUERIMIENTOS
Para poder instalar y arrancar la aplicación, es necesario tener instalado en el sistema:
- PHP 8.2 o superior
- MySQL (base de datos empleada en el proyecto)
- Git
- Composer (https://getcomposer.org/)
- Node.js
Nota: es posible que al tratar de instalar Composer surja algún error. 
Para instalarlo, es recomendable desactivar el antivirus y así evitar cualquier problema 
(es completamente seguro).


PROCESO DE INSTALACIÓN
1. Clonar repositorio
git clone https://github.com/sergioderon1803/TFG_RRSS_Cocina.git

2. Comprobar que el repositorio está correctamente vinculado
git remote -v
Debería aparecer algo como esto:
origin  https://github.com/usuario/repositorio.git (fetch)
origin  https://github.com/usuario/repositorio.git (push)

3. Configurar el fichero .env para conectar a la BBDD
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_BBDD
DB_USERNAME=root
DB_PASSWORD=password

4. Dentro de la carpeta del proyecto (/proyecto_recetas), instalar depencencias con composer (opcional)
composer install

5. Migrar tablas de la BBDD
php artisan migrate

6. Arrancar aplicación
php artisan serve

7. Compilar los assests (js, css, etc.)
npm run build

8. Acceder a la aplicación a través de localhost:8000


PARA DESARROLLADORES
- Recuperar actualizaciones del repositorio:
  git pull

- Subir cambios al repositorio
  git add .	o 	git add "nombre_archivo"

- Guardar cambios
  git commit -m "Mensaje de resumen de los cambios"

- Subir cambios al repositorio
  git push

Nota: puesto que estamos trabajando sobre la rama main directamente, asegurarse de tener 
la última actualización en local antes de subir cualquier cambio.

Nota 2: durante el desarrollo, para no compilar assest continuamente mientras se realizan cambios, 
usar npm run dev (compila mientras se realizan cambios).


