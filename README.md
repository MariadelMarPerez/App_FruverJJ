<p align="center">
  <h1>App_FruverJJ🍓</h1>
  <strong>Un sistema de gestión integral para 'FruverJJ'</strong>
  <br>
  <br>
  <strong>Estudiantes:</strong>
  <br>Ana Judith Velasquez
  <br>María del Mar Perez
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Node.js-339933?style=for-the-badge&logo=nodedotjs&logoColor=white" alt="Node.js">
</p>

---

## 📦 Módulos del Sistema

| Icono | Módulo | Descripción |
| :---: | :--- | :--- |
| *👤* | *Autenticación y Usuarios* | Gestión completa de *roles y permisos* (Administrador, Empleado, Cliente). Incluye control de acceso, registro y perfiles. |
| *🍎* | *Productos* | *CRUD* completo para productos. Permite la gestión de categorías, control de stock en tiempo real y ajuste de precios. |
| *💰* | *Punto de Venta (POS)* | Interfaz optimizada para el *registro ágil de ventas* en tienda. Generación de tickets y actualización automática de inventario. |
| *🛒* | *Pedidos en Línea* | Gestión y *seguimiento de estados* de pedidos web (pendiente, en preparación, enviado, entregado). |


---

## 🚀 Guía de Instalación y Puesta en Marcha

Sigue esta guía paso a paso para tener el proyecto funcionando en tu equipo local.

### ### 1. Prerrequisitos (Tu entorno de desarrollo)

Asegúrate de tener instalado lo siguiente en tu sistema:
* *XAMPP:* (o un servidor local similar con PHP, MySQL).
* *[Node.js](https://nodejs.org/) (Versión LTS):* Esencial para manejar las dependencias de frontend (JavaScript y CSS).
* **[Composer](https://getcomposer.org/download/):** El gestor de paquetes de PHP para instalar las librerías de Laravel.

### ### 2. Instalación y Configuración

1.  *Clonar el Repositorio:*
    Abre tu terminal (Git Bash) en la carpeta donde guardas tus proyectos (ej. C:/xampp/htdocs/) y ejecuta:
    bash
    git clone [https://github.com/MariadelMarPerez/App_FruverJJ.git](https://github.com/MariadelMarPerez/App_FruverJJ.git)
    cd App_FruverJJ
    

2.  *Instalar Dependencias de Backend (PHP):*
    Composer leerá el archivo composer.json e instalará todas las librerías de PHP que Laravel necesita.
    bash
    composer install
    

3.  *Instalar Dependencias de Frontend (Node.js):*
    NPM (Node Package Manager) leerá el archivo package.json e instalará todo lo necesario (como Vite, etc.) en una carpeta node_modules.
    bash
    npm install
    

4.  *Configurar el Entorno (.env):*
    Este es el paso *clave* para conectar tu proyecto con la base de datos.
    bash
    cp .env.example .env
    
    * Abre el archivo .env que acabas de crear.
    * Modifica las líneas de DB_DATABASE con tus datos de XAMPP:
        dotenv
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306  # Asegúrate que este puerto coincida con el de tu XAMPP
        DB_DATABASE=nombre_de_tu_bd_vacia
        DB_USERNAME=root
        DB_PASSWORD=
        

5.  *Generar Clave de Aplicación:*
    Laravel necesita esta clave de seguridad para funcionar.
    bash
    php artisan key:generate
    

### ### 3. Base de Datos y Ejecución

1.  *Crear las Tablas:*
    Asegúrate de haber creado una base de datos *vacía* en phpMyAdmin (con el nombre que pusiste en DB_DATABASE). Este comando creará todas las tablas por ti.
    bash
    php artisan migrate
    

2.  *(Opcional) Llenar con Datos de Prueba:*
    Si tienes "seeders" configurados, ejecútalos:
    bash
    php artisan db:seed
    

3.  *Compilar Archivos de Frontend:*
    Este comando "compila" todos los archivos JavaScript y CSS para que la aplicación los pueda usar.
    bash
    npm run dev
    
    (Deja esta terminal abierta, ya que se quedará "vigilando" los cambios en tus archivos de frontend).

4.  *¡Correr el Proyecto!*
    Abre una *nueva terminal* en la misma carpeta del proyecto e inicia el servidor de Laravel:
    bash
    php artisan serve
    

¡Listo! El proyecto debería estar corriendo. Abre tu navegador y ve a:
*[http://127.0.0.1:8000](http://127.0.0.1:8000)*

> *Nota Importante:* Debes acceder al proyecto usando la URL que te da artisan serve, *no* navegando a la carpeta desde el localhost de XAMPP.
