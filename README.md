# simbol
Servidor Web: Apache version 2.4
Manejador de Base de Datos: MySQL 5.6
PHP: Version 5.6.5 y posterio


BACKEND
  /simbolbackend
  
  Crear bd admin_simbol
  Cargar .env en la carpeta /simbolbackend
  
  -- Generar Migraciones
	php artisan migrate
  
  -- Correr Servidor
	php artisan serve
  

FRONTEND
  /simbol
  
  ingresar en http://localhost/simbol-web/simbol/
