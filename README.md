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

#### 3. Proyecto ya existente de Symfony
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

## Subiendo el proyecto a GitHub 
Tras crear la aplicación, en su versión "virgen", vamos a subirlo a Github para ir guardando los cambios.
- Creamos un nuevo repositorio en GitHub
- git init
- git branch -m main (ahora pasa de llamarse master a main)
- creamos un README.md
- git add .
- git commit -m "Inicio"
- git remote add origin git@github.com:VicenteCorts/Symfony-Firsts-Steps.git
- git push -u origin main

## Desplegar Symfony 
~En caso de la versión 6.4.* no ha sido necesario este apartado, pero por si algún día es de utilidad:~
### Apache
Debemos instalar un paquete para poder trabajar con servidores Apache (necesario también para crear un hostvirtual)
```html
composer require symfony/apache-pack
y
```
En caso de **error al instalar el symfony/apache-pack**:

1) Abrir el archivo composer.json (se encuentra en el directorio raíz del proyecto ) ir al final y cambiar a "true" el valor de la propiedad "allow-contrib". Por defecto viene en false:
```html
"extra": {
        "symfony": {
            "allow-contrib": true,
            "require": "6.4.*"
        }
    }
```
2) Ir a la consola y ejecutar el comando:
```html
composer remove symfony/apache-pack
```
3) Por último ejecutar el comando:
```html
composer require symfony/apache-pack
```

### Symfony Server
https://symfony.com/doc/current/setup/symfony_server.html
- Además de Ngnix o Apache podemos usar el servidor local de Symfony: (http://localhost:8000/)
```html
cd my-project/
symfony server:start
```

## CLASE 415
### VHOST
#### VICTOR ROBLES
Accedemos a: A:/wamp64/bin/apache/apache2.4.58/conf/extra/httpdvhost y añadimos un nuevo vhost:

```html
# Vhost para carpeta: 11aprendiendo symfony
#
<VirtualHost *:80>
	ServerName aprendiendo-symfony.com.devel
	DocumentRoot "a:/wamp64/www/master-php/11aprendiendo-symfony/public"
	<Directory  "a:/wamp64/www/master-php/11aprendiendo-symfony/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>
#
```
A continuación accedemos a C:\Windows\System32\drivers\etc\hosts con **vscode** para poder guardar como administrador y añadimos:
```html
127.0.0.1	aprendiendo-symfony.com.devel
::1	aprendiendo-symfony.com.devel
```
#### VICENTE CORTS
Accedemos a localhost y en el apartado inferior de la izquierda clicamos en "Añadir un host virtual". Rellenamos los inputs:
```html
aprendiendo-symfony.com.devel
a:/wamp64/www/master-php/11aprendiendo-symfony/public/
PHP: 8.3.0
```
Nuevamente en localhost nos vamos al apartado inferior derecha y clicamos en abrir nuestro nuevo vhost en una pestaña nueva

## CLASE 416
### Estructura de Symfony
(Victor Explica para 4.3, pero estamos en 6.4.* LTS)
- Carpeta bin (ejecutables de consola)
- config (configuración general de paquetes, desarollo,... incluye la carpeta packages y routes)
	+ packages
	+ routes
	+ bundles.php -> instalación de paquetes de symfony o de terceros
	+ routes.yml -> Trabajr con las rutas (web.php de laravel)
	+ services.yml -> configurar diferentes servicios en symfony
- migrations (para las migraciones)
- public (carpeta de entrada al proyecto)
- src: código principal con el que trabajaremos, simimlar a laravel
	+ Controller
 	+ Entity (para modelos)
	+ Repository (clases de tipo repositorio de consulta)
	+ Kernel.php: más configuraciones
- templates (vistas)
- test (para tests unitarios que queramos hacer y test en general)
- translations
- var (carpeta donde se almacena la caché y los logs)
- vendor (carpeta donde se instalan todos los paquetes y dependencias del framework)
- fichero .env (configuración de la BBDD y otros)
- .gitignore
-composer.json (versiones de paquetes)


## CLASE 417
Para ver los comandos disponibles por consola: **php bin/console help** o **php bin/console list**

### Crear Controladores
- Se ubican en src/Controller
- Se pueden crear manualmente o mediante consola; para consola el comando sería: **php bin/console make:controller HomeController**
Dentro del controlador observaremos los "use" y los métodos de la clase "HomeController
- **use**: incluye el ABstractController (controlador padre de Symfony), Response (para recoger datos por formularios) y Route (para hacer anotaciones para las rutas)
Ahora, escribiendo **"url"/home** en el navegador nos lleva directamente a una página; esto es por que al crear el controlador por consola nos genera una vista sencilla directamente en la **carpeta templates**, todo gracias al método index:
```html
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
```
### Hola Symfony
Si a este código incial le hacemos modificaciones como:
```html
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'hello' => 'Hola Symfony',
        ]);
    }
```
Podemos editar la vista para que muestre el segundo parámetro (hello) en vez de el primero:
```html
<div class="example-wrapper">
    <h1>{{ hello }}! ✅</h1>
</div>
```

## CLASE 418
### Configuraciones URL
Las rutas se definen justo encima del método al que dirigen: **#[Route('/home', name: 'app_home')]**
```html
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'hello' => 'Hola Symfony',
        ]);
    }
}
```
En el archivo config/routes.yml
- Para crear rutas nos dirigimos a este archivo y escribimos el siguinte código
```html
home:
    path: /inicio
    controller: App\Controller\HomeController::index
```
- Ahora escribiendo en el navegador **http://localhost:8000/inicio** nos redirige a la misma página que **http://localhost:8000/home**

### Programando:
Ahora crearemos otro método dentro de HomController
```html
    public function animales() {
        
        $title = 'Bienvenido a la página de animales';
        
        return $this->render('home/animales.html.twig',[
            'title' => $title,
        ]);
    }
```
- A continuación nos dirigiremos a la carpeta templates/home y crearemos un nuevo archivo clicando en **otros->PHP->html.twig**
- Ahora nos dirigimos a config/routes.yml y creamos su ruta: (es importante la estructura de la ruta -espacios y tabulaciones-)
```html
animales:
    path: /animales
    controller: App\Controller\HomeController::animales
```

## CLASE 419
###  Rutas Parámetro Obligatorio
la ruta se modificaría de la sigueinte manera, y een el controlador deberiamos añadir el parámetro de entrada y añadirlo al return, para poder añadirlo en la vista:
```html
RUTA

animales:
    path: /animales/{nombre}
    controller: App\Controller\HomeController::animales
---------------------------------------------
CONTROLADOR

    public function animales($nombre) {
        
        $title = 'Bienvenido a la página de animales';
        
        return $this->render('home/animales.html.twig',[
            'title' => $title,
            'nombre' => $nombre,
        ]);
    }
---------------------------------------------
VISTA

<h1>{{ title }}</h1>
<h2>{{ nombre }}</h2>
```
### Parámetro Opcional
- **Primera Opción**: Sería añadiendo el símbolo "?" al parámetro de la ruta (path: /animales/{nombre?})
- **Segunda Opción**: Añadiendo en la ruta:
```html
animales:
    path: /animales/{nombre}
    controller: App\Controller\HomeController::animales
    defaults: {nombre: 'GATO'} //ESTA LÍNEA
```
De este modo generamos un **valor default** para el parámetro en caso de que no exista

## CLASE 420
###












