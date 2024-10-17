# Primeros pasos Symfony (6.4.12 LTS)

## CLASE 414
### Requisitos previos
Para trabajar con Symfony 6.4.12 será necesario tener:
- Un servidor de aplicación web (en mi caso **wamp**)
- Composer (https://getcomposer.org/)
- Php 8.1.0 o superior (en mi caso tengo instalado php 8.3 en wamp)
- **Recomendable** instalar Symfony CLI, que brinda un binario útil llamado **Symfony** que proporciona todas las herramientas que necesita para desarrollar y ejecutar su aplicación Symfony localmente.

### Tipos de Instalación de Symfony 6.4.
- Abrimos la consola de comandos
- Nos dirigimos al direcitorio donde queremos instalar el proyecto
- **symfony check:requirements** Para ver si nos falta alguno de los requisitos solicitados
Podemos enfocar el proyecto de dos maneras: 

#### 1. API Rest
Backed resfull que pueden ser consumidos con fronted realizado con Angular por ejemplo. Backend separado del Frontend (muhcos menos componentes, más compacto y optimizado). Este estilo de instalación deja muchos otros paquetes sin instalar como twig (gestor de vistas).
```html
symfony new my_project_directory --version="6.4.*"
---------------------------------------------------------------------
composer create-project symfony/skeleton:"6.4.*" my_project_directory
```
#### 2. Aplicaciones web
Paquete completo, aplicaciones "monolíticas" e independientes: backend + frontend
```html
symfony new my_project_directory --version="6.4.*" --webapp
----------------------------------------------------------------------
composer create-project symfony/skeleton:"6.4.*" my_project_directory
cd my_project_directory
composer require webapp
```

#### Proyecto ya existente de Symfony
Para levantar un proyecto ya existente ejecutaremos las siguientes líneas en la consola de comandos en vez de las anteriores:
```html
# clone the poroject to download its contents
cd projects/
git clone ...

# make Composer install the project's dependecies into vendor/
cd my-project/
composer install
```
- Posteriormente será necesario hacer cambiso en el archivo **.env**, configurar la **base de datos**, etc.
- puede ser útil ejecutar el comando: **php bin/console about** para mostrar información sobre el proyecto.

### Desplegar Symfony 
#### Apache
Debemos instalar un paquete para poder trabajar con servidores Apache (necesario también para crear un hostvirtual)
```html
composer require symfony/apache-pack
y
```
#### Symfony Server
Además de Ngnix o Apache podemos usar el servidor local de Symfony: (http://localhost:8000/)
```html
cd my-project/
symfony server:start
```
## CLASE 414.1
### Subiendo el proyecto a GitHub
Tras crear la aplicación, en su versión "virgen", vamos a subirlo a Github para ir guardando los cambios.
- Creamos un nuevo repositorio en GitHub
- git init
- git branch -m main (ahora pasa de llamarse master a main)
- creamos un README.md
-git add .
- git commit -m "Inicio"
- git remote add origin git@github.com:VicenteCorts/Symfony-Firsts-Steps.git
- git push -u origin main

## CLASE 415
###