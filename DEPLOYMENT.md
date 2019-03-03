# Procedimiento para el despliegue de inventarios en un servidor

Estos pasos siguientes son la manera en que se puede montar la aplicación de inventarios en un servidor en Heroku.

**1. Creación de cuenta**
Crear una cuenta en [Heroku](https://www.heroku.com/)

**2. Crear una nueva aplicación**
Iniciar la creación de una nueva aplicación pulsando el botón de **New** y después pulsar **Create new app**

**3. Llenado de información**
- Escribir el nombre de la aplicación (El cual será parte del dominio también)
- Seleccionar la región en la que se encontrará
- Pulsar el botón **Create app**

**4. Añadir el archivo Procfile en el  proyecto**
Crear un archivo llamado **Procfile** (Sin extensión) en la raíz del proyecto que contenga la siguiente línea:
```
web: vendor/bin/heroku-php-apache2 public
```

**5. Añadir dependencias**
Añadir las siguiente dependencia en el archivo ```/composer.json```
```
"post-install-cmd":  [
"php artisan clear-compiled",
"php artisan optimize",
"chmod -R 777 public/"
]
```
Luego correr el comando:
```
composer install
```
Finalmente, correr el comando:
```
composer update
```

**6. Comando para subir la aplicación a Heroku**
Encontrándose en ```/inventarios``` correr los siguientes comandos:
```
heroku login
```
Iniciar sesión con las credenciales de la cuenta creada.
```
git init
```
```
heroku git:remote -a nombreDeLaAplicacionRegistrada
 ```
 
```
git add .
```

```
git commit -am "Primera versión del proyecto en línea"
```

```
git push heroku master
```