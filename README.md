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
### Rutas Avanzadas
## Método (POST GET ...)
Podemos añadir la palabra clave **methods** para especificar el método de llegada de los parámetros
```html
animales:
    path: /animales/{nombre}/{apellidos}
    controller: App\Controller\HomeController::animales
    defaults: {nombre: 'GATO', apellidos: 'Y perros'}
    methods: [GET, PUT]
```
## Requeriments
Podemos añadir expresiones regulares para los parámetros; por ejemplo que nombre sea con letras y apellidos con numeros:
```html
animales:
    path: /animales/{nombre}/{apellidos}
    controller: App\Controller\HomeController::animales
    defaults: {nombre: 'GATO', apellidos: 'Y perros'}
    methods: [GET, PUT]
    requirements:
        nombre: '[a-zA-Z]+'
        apellidos: '[1-9]+'
```
https://symfony.com/doc/4.x/routing.html

## CLASE 421
### Redirecciones
Cremaos un nuevo método en HomeController:
```html
    public function redirigir() {
        return $this->redirectToRoute('home');
    }
```
- Redigirimos mediante el return al método/ruta **"home"**
Y en Rutas creamos una nueva ruta para poder enlazar esto:
```html
redirigir:
    path: /redirigir
    controller: App\Controller\HomeController::redirigir
```
### Redirecciones 301
Muy recomendables de cara a SEO:
```html
    public function redirigir() {
        return $this->redirectToRoute('home', array(), 301);
    }
```
### Otro método para redirecciones
```html
    public function redirigir() {
        return $this->redirect('http://localhost:8000/inicio');
    }
```
**EN CASO DE PROBLEMAS DE CACHÉ** -> borrar **carpeta var/cache** entera

## CLASE 422
### Introducción a Twig
(...)

## CLASE 423
### Plantillas y Bloques
Creamos una carpeta nueva dentro de templates (layouts) y dentro una nueva plantilla terminada en .html.twig y trabajaremos sobre ella
- Creamos una estructura básica de html (<!DOCTYPE HTML> (...)
- Para definir bloques sería d ela sigueinte manera:
```html
{% block titulo %} INICIO {% endblock %} - Master en PHP Vicente Corts
```
- Al cargar diferentes páginas podemos hacer que el bloque titulo varía su contenido; ahora mismo está definido como "INICIO" pero más tarde podremos cambiarlo
- Completamos la plantilla para hacer uso de ella
```html
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>
            {% block titulo %} INICIO {% endblock %} - Master en PHP Vicente Corts
        </title>
    </head>
    <body>
        <div id="header">
            {% block cabecera %}
                <h1>Cabecera de la plantilla</h1>
            {% endblock %}
        </div>
        <section id="content">
            {% block contenido %}
                <p>Contenido default</p>
            {% endblock %}
        </section>
        <footer>
            Footer default
        </footer>
    </body>
</html>
```
- Para usarla, crearemos en otra vista la instrucción **extends** (Modificamos la plantilla animales.html.twig
```html
{% extends 'layouts/master.html.twig' %}
--------------------------------------------
ANIMALES.HTML.TWIG

{% extends 'layouts/master.html.twig' %}

{% block titulo %}
    Animales
{% endblock %}

{% block cabecera %}
    <h1>Animales</h1>
{% endblock %}

{% block contenido %}
    <h1>{{ title }}</h1>
    <h2>{{ nombre }}</h2>
    <h2>{{ apellidos }}</h2>
{% endblock %}
```
- Si quisiéramos heredar un bloque además de añadirle algo de contenido sería mediante**{{ parent() }}**:
```html
{% block cabecera %}
    {{ parent() }}
    <h1>Animales</h1>
{% endblock %}
```

## CLASE 424
### Pasar Parámetros a nuestra vista
Ya visto anteriormente (Parámetros previamente definidos en el controlador):
```html
<h1>{{ title }}</h1>
<h2>{{ nombre }}</h2>
<h2>{{ apellidos }}</h2>
```
### Comentarios en Twig
Ahora veremos como crear **Comentarios** usando "{# (...) #}":
```html
{#    <h1>{{ title }}</h1>
    <h2>{{ nombre }}</h2>
    <h2>{{ apellidos }}</h2>
#}
```
### Variables en Twig
Para crear variables sería con {% set nombre = valor %}
```html
    {% set perro = 'Pastor aleman' %}
    <h4>{{ perro }}</h4>
```
## CLASE 425
### Definir y mostrar arrays
En primer lugar crearemos un array en HomeController método: animales para trabajar con él:
```html
$animales = array ('perro', 'gato', 'paloma', 'rata');

---

        return $this->render('home/animales.html.twig',[
            'title' => $title,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'animales' => $animales,
        ]);
```
Mediante el código **{{ dump(animales) }}** podemos ver el contenido del array que hemos enviado a la plantilla a través del HomeController
- Para mostrar un elemento concreto pasamos el puesto del array por corchetes: **{{ animales[1] }}**
- Para trabajar con **arrays asociativos**
```html
        $aves = array (
            'raza' => 'palomo',
            'loro' => 'pirata',
            'paloma' => 17,
            'aguila' => true
        );
------------------------------------------
    {# Trabajar con array asociativo #}
    {{ dump(aves) }}
    {{ aves.loro }}
```
También podemos concatenar cosas dentro de las claves mediante el uso de la virgulilla 
```html
{{ aves.loro ~ ' ' ~ aves.paloma }}
```
## CLASE 426
### Estructuras de Control en Twig
#### IF
```html
    {% if aves.paloma == 17 %}
        <h1> IF COMPLETADO </h1>
    {% else %}
        <h2> No hay CONDICION CUMPLIDA </h2>
    {% endif %}
```
#### FOREACH
```html
    {% if animales|length >= 0 %}
        <ul>
            {% for animal in animales %}
                <li>
                    {{ animal }}
                </li>
            {%  endfor %}
        </ul>
    {% endif %}
--------------------------------------------
    {% for i in 0..10 %}
        {{ i }}
    {% endfor %}
```
- El ".." indica el rango
- Endfor tiene variantes -> revisar documentación

## CLASE 427
### Starts End
```html
    {# Starts Ends#}
    {% if aves.raza starts with 'p' %}
        <h1>Empieza por P</h1>
    {% endif %}
    
    {% if aves.raza ends with 'o' %}
        <h1>Termina en O</h1>
    {% endif %}
```
## CLASE 428
### Funciones predefinidas en Twig
https://twig.symfony.com/doc/  --> Nos dirigimos a la parte de functions.
#### min
extrae el número minimo de un array
```html
{{ min ([2,4,6,1,8,12]) }}
```
#### max
extrae el número mayor de un array
```html
{{ max ([2,4,6,1,8,12]) }}
```
#### random
```html
{{ random(animales) }}
```
#### range
- En el siguiente ejemplo recorrerá del 0 al 100 en rangos de 12 en 12:
```html
    {% for i in range(0,100, 12) %}
        {{ i }}
        <br>
    {% endfor %}
```
## CLASE 429
### Includes
Creamos una nueva carpeta "partials" dentro de templates para dividir el código que tenemos hasta ahora.
```html
    {{ include('partials/funciones.html.twig') }}

{# INCLUSO PODEMOS PASAR PARÁMETROS: #}
    {{ include('partials/funciones.html.twig', {'nombre':'Vicente Corts León'}) }}
```
## CLASE 430
### Filtros por Defecto
Pipes o tuberías que nos permiten modificar el resultado final de una variable. https://twig.symfony.com/doc/  --> Filtros
```html
    {# Filtros #}
    {{ animales|length }}
    {% set email = '  email@email.com ' %}
    {{ email|trim }}
    {{ email|upper }}
    {{ email|lower }}
```
Existen muchos filtros, ahí dejo un par de ellos con funcionalidades básicas.
## CLASE 431
### Crear Extensiones
Podemos crear nuestro propio filtro o helper para que ejecute una función específica que nosotros queramos
- Para ello crearemos una carpeta llamada "Twig" dentro de la carpeta raiz src
- nos dirigimos a la consola y ejecutamos el comando **php bin/console list**
- El resultado nos muestra que podmeos crear la extensión-twig directamente por consola
- **php bin/console make:twig-extension "nombre"**
- Por defecto nos genera una clase MiFiltroExtension con dos carpetas
- Nos dirigimos a la otra carpeta creada "Runtime"
- Creamos un tercer método llamado **multiplicar** a través del cual haremos qe se multiplique una variable
```html
    public function multiplicar($numero) {
        $tabla = "<h1>Tabla del $numero</h1>";
        for ($i = 0; $i <= 10; $i++) {
            $tabla .= "$i X $numero = " . ($i * $numero) . "<br/>";
        }

        return $tabla;
    }
```
- En el archivo Extension/MiFiltroExtension.php
- sustituimos 'doSomething' de los métodos anteriores por 'multiplicar' (nuestro nuevo método)
- igualmente sustituimos los nombres de la función en los otros métodos a **multiplicar**
```html
class MiFiltroExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('multiplicar', [MiFiltroRuntime::class, 'multiplicar']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('multiplicar', [MiFiltroRuntime::class, 'multiplicar']),
        ];
    }
}
```
Una vez que tenemos nuestra exrtensión hecha, debemos registrarla dentro de los servicios de Symfony (config/services.yaml)
```html
    App\Twig\Extension\:
        resource: '../src/Twig/Extension'
        tags: ['twig.extension']
```
¡MUCHO CUIDADO CON LOS ESPACIOS Y TABULACIONES!

- Añadimos por último al archivo animales.html.twig código para hacer uso de la nueva extensión
```html
    {# Filtros #}
    {{ multiplicar(4)|raw }}
    {{ 12|multiplicar|raw }}
```
## CLASE 432
### Listar Rutas
En consola mediante el comando: **php bin/console debug:router**

## CLASE 433
### Conexión a la BBDD
https://symfony.com/doc/6.4/doctrine.html#configuring-the-database
- Nos dirigimos al fichero .env de la raíz del proyecto
- Entre las variables que encontramos existe **APP_ENV=dev** -> Esta se puede alternar entre dev(desarollo) o prod (producción)
- Otra de las variables es **DATABASE_URL** cuyo contenido permite conectarnos a la base de datos
	- DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
> primera parte, tipo de BBDD ->"postgesql://...
> segunda parte, usuario de la base de datos -> app: (temrinado en dos puntos, en caso de que no haya contraseña omotir esos dos puntos)
> tercera parte, contraseña-> !ChangeMe!@ (el @ es necesario ponerlo independientemente de que haya o no contraseña)
> cuarta parte, dirección url -> 127.0.0.1:5432/
> quinta parte, nombre de la BBDD -> app?
> sexta parte, versión del server -> serverVersion=16
> septima parte, charset -> &charset=utf8"

En mi caso, sería así: ** DATABASE_URL="mysql://root@127.0.0.1:3306/symfony_db?serverVersion=8.2.0&charset=utf8mb4"**
- A continuación escribimos por consola: **php bin/console doctrine:databas:create** para crear la BBDD

## CLASE 434
### Generar Entidades desde la BBDD

#### **DEPRECATED**

Nos dirigimos a la carpeta src/Entity -> aqui es donde se guardarán las Entidades. Podemos hacerlas a mano o generando entidades a través de la BBDD
- Creamos una tabla en la BBDD (animales)
- Nos dirigimos a la consola -> **Debemos pasar la BBDD a código compatible con Symfony y Doctrine**
- **php bin/console doctrine:mapping:convert --from-database yml ./src/Entity**


#### **DEPRECATED**

## CLASE 435
### Generar Entidades desde Symfony
Este método es más recomendable y el que usan todas la guías de Symfony y la propia documentación Oficial (https://symfony.com/doc/6.4/doctrine.html#configuring-the-database)
- Para crear entidades mediante Symfony nos dirigiremos a la consola con el comando: **php bin/console make:entity nombre**
- Creamos la entidad "Usuario"
- Al ejecutar el comando, nos crea el archivo de la propia entidad y un repositorio en el que añadir métodos complejos
- Continuamos con la creación de la entidad por consola añadiendo campos a la entidad (que posteriormente mediante el ORM pasarán a ser columnas de la BBDD)

En caso de que la Entidad no tenga incluidos los setters y getters debemos escribir por consola **php bin/console make:entity --regenerate**
(...)

## CLASE 436
### Generar tablas de entidades
https://symfony.com/doc/6.4/doctrine.html#migrations-creating-the-database-tables-schema
- Primero ejecutarmeos el comando: **php bin/console doctrine:migrations:diff** para que ejecute las migraciones necesarias en base a una comparación con la BBDD. Solo creará migraciones de aquellas tablas que no estén en la BBDD. También puede crearse el archivo de la migración mediante **php bin/console make:migration**
- Ahora debemos ejecutar las migraciones para que estas queden plasmadas en la BBDD: **php bin/console doctrine:migrations:migrate**


## CLASE 437
### ERROR POR MI CUENTA
> Crear código de una tabla ya existente en la BBDD:
> En primer lugar, hacer mención al comando **php bin/console doctrine:mapping:import APP\Entity annotation --path=src/Entity**, el cual es usado por Victor para extraer una tabla ya creada en la BBDD al formato ORM para poder trabajar con ella en Symfony directamente. A mi me da fallos.
> - Los fallos son por "doctrine:mapping..." is not defined
> - Aun isntalanto los paquetes que me dice ChatGPT no da resultado (composer require doctrine/orm || composer require doctrine/doctrine-bundle | composer require doctrine/doctrine-migrations-bundle)
> - Finalmente me dice que puede que me **faltan extensiones en php.ini (php -m | grep pdo_mysql)** - Pero estas no las he instalado.
> - Continúo sin hacer esto.
> 
> https://symfony.com/doc/6.4/doctrine/reverse_engineering.html  (A hacerlo manualmente) -> Caution The doctrine:mapping:import command used to generate Doctrine entities from existing databases was deprecated by Doctrine in 2019 and there's no replacement for it. Instead, you can use the make:entity command from Symfony Maker Bundle to help you generate the code of your Doctrine entities. This command requires manual supervision because it doesn't generate entities from existing databases.

### Hacer cambios en Entidades
Decidimos cambiar el nombre de la entidad "Animales" a "Animal" -> Cambiamos manualmente tanto el archvio de la Entidad como del Repository que se crea con ella; igualmente modificamos el nombre de las clases que incluyen estos archivos.
- Tras esto, generamos una migración: **php bin/console make:migration**
- Luego la ejecutamos: **php bin/console doctrine:migrations:migrate**
- Finalmente en la BBDD se cambia el nombre de la tabla Animales por "Animal" (TODO OK)

### Añadir nuevo campo a una Entidad ya existente
```html
$ php bin/console make:entity

Class name of the entity to create or update
> Animal

New property name (press <return> to stop adding fields):
> cantidad

Field type (enter ? to see all types) [string]:
> int

Can this field be null in the database (nullable) (yes/no) [no]:
> no

New property name (press <return> to stop adding fields):
>
(press enter again to finish)
```
De este modo nos añade el código para incluir este nuevo atributo, así como sus getters y setters.
- Ahora debemos, una vez más, crear la migración y ejecutarla.

## CLASE 438
### Guardar en la BBDD
https://symfony.com/doc/current/doctrine.html#persisting-objects-to-the-database
- Comenzaremos por crearnos un nuevo controlador para Animal **php bin/console make:controller AnimalController**
- Con esto nos creará el controlador dentro de src/controller y una plantilla index.html.twig dentro de templates/animal
- Aunque en el propio controlador nos crean una ruta default, nos dirigiremos a config/routes.yaml y crearemos una ruta adicional
```html
animal_index:
    path: /animal/index
    controller: App\Controller\AnimalController::index
```
Ahora crearemos un método dentro de AnimalController "save" para añadir registros a la BBDD
- Deberemos cargar **use Symfony\Component\HttpFoundation\Response;** en caso de que no esté por defecto
- Igualmente debemos cargar el modelo; es decir la Entidad Animal: **use App\Entity\Animal;**
- Para poder añadir registros a la BBDD necesitamos trabajar con el "EntityManager", incluimos **use Doctrine\ORM\EntityManagerInterface;**
- Luego en el método save deberemos incluirlo:
```html
    public function save(EntityManagerInterface $entityManager): Response
    {
        //Crear el Objeto Animal
        $animal = new Animal();
        $animal->setTipo(Perro);
        $animal->setColor(azul);
        $animal->setRaza(husky);
        $animal->setCantidad(27);
                
        //Invocar doctrine para que guarde el objeto
        $entityManager->persist($animal);
        //Ejecutar orden para que doctrine guarde el objeto
        $entityManager->flush();
        
        //Respuesta
        return new Response('Nuevo Animal guardado con el id'.$animal->getId());
    }
```
- Importante la forma en la que se declara la función y las últimas líneas de codigo para hacer el insert
Igualmente, creamos una ruta para el método save en routes.yaml:
```html
animal_save:
    path: /animal/save
    controller: App\Controller\AnimalController::save
```
- **$entityManager->flush();** -> Whether you're creating or updating objects, the workflow is always the same: Doctrine is smart enough to know if it should INSERT or UPDATE your entity.

## CLASE 439
### Comandos SQL
Para comprobar que todo ha funcionado correctamente podemos crear un SELECT de la tabla modificada por consola: **php bin/console dbal:run-sql 'SELECT * FROM animal'**

## CLASE 440
### Find (Extraer registro de la BBDD)
- https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
Creamos una ruta:
```html
animal_detail:
    path: /animal/{id}
    controller: App\Controller\AnimalController::animal
```
Creamos un método "animal" en AnimalController
```html
    public function animal(EntityManagerInterface $entityManager, int $id):Response 
    {
        //Cargar Repositorio y Consulta "find"
        $animal_repo = $entityManager->getRepository(Animal::class)->find($id);
        
        if (!$animal_repo) {
            throw $this->createNotFoundException(
                'No existe un registro en la tabla animal con el id: '.$id
            );
        }

        return new Response('El animal con ese id es: '. $animal_repo->getTipo());
    }
```
## CLASE 441
### Find All
Modificamos el método index de Animalcontroller:
```html
    public function index(EntityManagerInterface $entityManager): Response
    {
        //Cargar Repositorio 
        $repository = $entityManager->getRepository(Animal::class);
        
        //Consulta find-all
        $animales = $repository->findAll();
                
                
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales' => $animales,
        ]);
    }
```
Nos dirigimos a la vista y la modificamos para poder mostrar el resultado del método anterior (el índice 'animales' que se envía por el return
```html
    <ul>
    {%for animal in animales %}
        <li>
            <ul>
                <li>{{ animal.id }}</li>
                <li>{{ animal.tipo }}</li>
                <li>{{ animal.color }}</li>
                <li>{{ animal.raza }}</li>
            </ul>
        </li>
    {% endfor %}
    </ul>
```
## CLASE 442
### Tipos de Find
https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
- Ejemplos empleados
```html
        //find donde se cumple la condición tipo = perro
        $animales = $repository->findBy([
            'tipo' => 'Perro'
        ]);
        
        //find donde se cumple la condición tipo = perro Pero solo saca la priemra coincidencia
        $animales = $repository->findOneBy([
            'tipo' => 'Perro'
        ]);
        
        //find donde se cumple la condición tipo = perro && Ordenación descendente
        $animales = $repository->findBy([
            'tipo' => 'Perro'
        ], [
            'id' => 'DESC'
        ]);
```
Revisando la docuemtnación encontramos más ejemplos de Find:
```html
$repository = $entityManager->getRepository(Product::class);

// look for a single Product by its primary key (usually "id")
$product = $repository->find($id);

// look for a single Product by name
$product = $repository->findOneBy(['name' => 'Keyboard']);
// or find by name and price
$product = $repository->findOneBy([
    'name' => 'Keyboard',
    'price' => 1999,
]);

// look for multiple Product objects matching the name, ordered by price
$products = $repository->findBy(
    ['name' => 'Keyboard'],
    ['price' => 'ASC']
);

// look for *all* Product objects
$products = $repository->findAll();
```
- Primero se carga el repositorio y despues se emplean los diferentes ejemplos de find con el objeto "product"

## CLASE 443
### Conseguir Objeto Automático
https://symfony.com/doc/current/doctrine.html#automatically-fetching-objects-entityvalueresolver
- Nos dirigimos al método animal
- Comentamos el contenido del método y modificamos
- Para conseguir de manera automática un objeto de la BBDD haremos lo siguiente:
```html
    public function animal(Animal $animal):Response 
    {
        return new Response('El animal con ese id es: '. $animal->getTipo());
    }
```
Al cambiar el parámetro $id por $animal, Doctrine reconoce directamente nuestra intención y hace una call al objeto, para ello solo debemos pasar por la url el número del 1 del objeto al que qeremos qe haga referencia

## CLASE 444
### Actualizar Registros
- https://symfony.com/doc/current/doctrine.html#updating-an-object
Creamos un nuevo método "update":
```html
    public function update(EntityManagerInterface $entityManager, int $id): Response {
        //Cargar doctrine
        //Cargar entityManager
        //Ya lo hacemos en los paréntesis de la función
        
        //Cargar Repo Animal
        $em = $entityManager->getRepository(Animal::class);

        //Find para conseguir el objeto
        $animal = $em->find($id);

        //Comprobar si el bojeto llega
        if (!$animal) {
            $message = 'No existe un registro en la tabla animal con el id: ' . $id;
        } else {
            //Asignarle valores al objeto capturado
            $animal->setTipo('Koala '.$id);
            $animal->setColor('Amarillo');
            $animal->setRaza('de la Patagonia');
            $animal->setCantidad(13);
            
            //Persistir en doctrine - No es necesario para actualizaciones
            $entityManager->persist($animal);
            
            //Guardar en la BBDD
            $entityManager->flush();
            
            $message = "El animal ha sido actualizado id: ". $animal->getId();
        }

        //Respuesta
        return new Response($message);
    }
```
Creamos la ruta para el método update:
```html
animal_update:
    path: /animal/update/{id}
    controller: App\Controller\AnimalController::update
```

## CLASE 445
### Eliminar Registro de la BBDD



























