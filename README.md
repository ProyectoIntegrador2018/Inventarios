# Inventarios

Aplicación web para llevar el control del inventario de dispositivos del departamento de Computación

## Tabla de contenidos

* [Detalles del cliente](#detalles-del-cliente)
* [URLS del ambiente](#urls-del-ambiente)
* [Equipo](#equipo)
* [Herramientas administrativas](#herramientas-administrativas)
* [Configurar el proyecto](#configurar-el-proyecto)
* [Correr el stack para desarrollo](#correr-el-stack-para-desarrollo)
* [Detener proyecto](#detener-proyecto)
* [Restaurar base de datos](#restaurar-base-de-datos)
* [Depuración(Debbuging)](#depuración)
* [Especificaciones para correr proyecto](#especificaciones-para-correr-proyecto)
* [Checar código para problemas potenciales](#checar-código-para-problemas-potenciales)


### Detalles del cliente

| Nombre                      | Email             | Role                                      |
| --------------------------- | ----------------- | ----------------------------------------- |
| Armandina Juana Leal Flores | aleal@tec.mx      | Directora del Departamento de Computación |


### URLS del ambiente

* **Producción** - [Heroku](http://inventariosdecomputacion.herokuapp.com/)
* **Desarrollo** - [Github](https://github.com/ProyectoIntegrador2018/Inventarios)

### Equipo

| Nombre            | Email                          | Role        |
| ----------------- | ------------------------------ | ----------- |
| Miguel Banda      | miguelangelbandardz@gmail.com  | Development |
| Abelardo Gonzalez | abelardo_gonzalezg@hotmail.com | Development |
| Luis Rojo         | luis_alfonso_96@hotmail.com    | Development |
| Guillermo Mendoza | ams.guillermo@gmail.com        | Development |

### Herramientas administrativas

You should ask for access to this tools if you don't have it already:

* [Github repo](https://github.com/ProyectoIntegrador2018/Inventarios)
* [Backlog](linktobacklog)
* [Heroku](https://crowdfront-staging.herokuapp.com/)
* [Documentation](linktodocumentation)

## Desarrollo

### Configurar el proyecto

Para el desarollo local del proyecto es necesario instalar [`Composer`](https://getcomposer.org/), el cual es un manejador de dependencias de PHP el cual nos servirá para el manejo de la aplicación en cuestión de instalación y actualización de paquetes del proyecto.

1. Clonar este repositorio en tu equipo:

```bash
$ git clone https://github.com/ProyectoIntegrador2018/Inventarios.git
```

2. Instalar y/o actualizar dependencias de ser requerido

```bash
$ composer install
$ composer update
```

3. Generar llave del proyecto

```
$ php artisan key:generate
```

4. Migrar la base de datos

```
$ php artisan migrate
```

### Correr el stack para desarrollo

1. Correr en la terminal

```
$ php artisan serve
```

** A partir de aquí se dejará el documento intacto dado que no se ha llegado a la parte de programación del proyecto que nos permite decidir cuales serán los comando y herramientas definitivas para los siguiente puntos requeridos.**

Este comando ejecutará las rutinas necesarias para poder acceder al proyecto en `localhost:8000`


It may take a while before you see anything, you can follow the logs of the containers with:

```
$ docker-compose logs
```

Once you see an output like this:

```
web_1   | => Booting Puma
web_1   | => Rails 5.1.3 application starting in development on http://0.0.0.0:3000
web_1   | => Run `rails server -h` for more startup options
web_1   | => Ctrl-C to shutdown server
web_1   | Listening on 0.0.0.0:3000, CTRL+C to stop
```

This means the project is up and running.

### Detener proyecto

In order to stop crowdfront as a whole you can run:

```
% plis stop
```

This will stop every container, but if you need to stop one in particular, you can specify it like:

```
% plis stop web
```

`web` is the service name located on the `docker-compose.yml` file, there you can see the services name and stop each of them if you need to.

### Restaurar base de datos

You probably won't be working with a blank database, so once you are able to run crowdfront you can restore the database, to do it, first stop all services:

```
% plis stop
```

Then just lift up the `db` service:

```
% plis start db
```

The next step is to login to the database container:

```
% docker exec -ti crowdfront_db_1 bash
```

This will open up a bash session in to the database container.

Up to this point we just need to download a database dump and copy under `crowdfront/backups/`, this directory is mounted on the container, so you will be able to restore it with:

```
root@a3f695b39869:/# bin/restoredb crowdfront_dev db/backups/<databaseDump>
```

If you want to see how this script works, you can find it under `bin/restoredb`

Once the script finishes its execution you can just exit the session from the container and lift the other services:

```
% plis start
```

### Depuración

We know you love to use `debugger`, and who doesn't, and with Docker is a bit tricky, but don't worry, we have you covered.

Just run this line at the terminal and you can start debugging like a pro:

```
% plis attach web
```

This will display the logs from the rails app, as well as give you access to stop the execution on the debugging point as you would expect.

**Take note that if you kill this process you will kill the web service, and you will probably need to lift it up again.**

### Especificaciones para correr proyecto

To run specs, you can do:

```
$ plis run test rspec
```

Or for a specific file:

```
$ plis run test rspec spec/models/user_spec.rb
```

### Checar código para problemas potenciales

To run specs, you can do:

```
$ plis run web reek
```

```
$ plis run web rubocop
```

```
$ plis run web scss_lint
```

Or any other linter you have.
