# Documentación de la API de Customer Management

## Descripción General

La API de Customer Management proporciona endpoints para administrar clientes y sus detalles asociados, como regiones y comunas.

## Requisitos

- Se requiere PHP 8 o superior.
- POSTMAN o cualquier aplicacion para pruebas de API

## Clonado y Configuracion

1. Primero clonamos el repositorio

``` ```

2. Abrimos el proyecto y copiaremos el contenido del archivo .env.example a un nuevo archivo .env

3. Descargamos las depencencias de Laravel con:

`composer install`

4. Abriremos una terminal del proyecto y ejecuraremos el comando:

`php artisan key:generate`

Esto nos generara una nueva APP_KEY para el proyecto.

5. Declararemos la variable de entorno LOG_OUTPUT_ONLY=false bajo a las demas variables de LOG:

``LOG_CHANNEL=stack``
``LOG_STACK=single``
``LOG_DEPRECATIONS_CHANNEL=null``
``LOG_LEVEL=debug``
``LOG_OUTPUT_ONLY=false``

Con esta variable controlaremos los logs que se guardaran estando en produccion o desarrollo en el archivo api.log

6. Colocamos la variable APP_DEBUG en false:

`APP_DEBUG=false`

7. Configuramos las varibles de entorno para la base de datos que utilicemos:

``DB_CONNECTION=``
``DB_HOST=127.0.0.1``
``DB_PORT=``
``DB_DATABASE=``
``DB_USERNAME=``
``DB_PASSWORD=``

8. Ahora haremos las migraciones y crearemos los registros para las regiones y las comunas:

`php artisan migrate`
`php artisan db:seed --class=RegionCommunSeeder`

Esto nos creara 10 registros de regiones y 5 de comunas por cada region, adicionalmente se puede ejecutar 5 registros de customer tambien usando:

`php artisan db:seed --class=CostumerSeeder`

## Endpoints 

### 1. Registro de usuario
- **URL:** `/api/register`
- **Método:** `POST`
- **Descripción:** Registra un nuevo usuario en el sistema.
- **Parámetros de Solicitud:**
  - `name`: Nombre del usuario (cadena, obligatorio)
  - `email`: Correo electrónico del usuario (email, obligatorio, unico)
  - `password`: Contraseña del usuario (password, obligatoria, confirmada)
- **Respuesta Exitosa (Código 201):** CREATED
```
{
    "name": "julio",
    "email": "prueba@gmail.com",
    "updated_at": "2024-03-29T03:31:58.000000Z",
    "created_at": "2024-03-29T03:31:58.000000Z",
    "id": 2
}
```
- **Respuesta de Error (Código 400):**
```
{
    "message": "The name field is required. (and 2 more errors)",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```
### 2. login
- **URL:** `/api/login`
- **Método:** `POST`
- **Descripción:** Crea una nueva sesion de usuario y genera el JWT.
- **Parámetros de Solicitud:**
  - `email`: Correo electrónico del usuario (email, obligatorio)
  - `password`: Contraseña del usuario (password, obligatoria)
- **Respuesta Exitosa (Código 200):** OK
```
{
    "token": "3|andres@gmail.com2024-03-29 05:05:28477"
}
```
- **Respuesta de Error (Código 401):** Unauthorize
```
{
    "message": "User profile Ok",
    "userData": {
        "id": 1,
        "name": "andres",
        "email": "andres@gmail.com",
        "created_at": "2024-03-28T17:06:17.000000Z",
        "updated_at": "2024-03-28T17:06:17.000000Z"
    }
}
```
### 3. Perfil de usuario
- **URL:** `/api/user`
- **Método:** `GET`
- **Descripción:** Consulta los datos del usuario que inicio sesion en el sistema.
- **Parámetros de Solicitud:**
- **Respuesta Exitosa (Código 200):** OK
```
{
    "name": "julio",
    "email": "prueba@gmail.com",
    "updated_at": "2024-03-29T03:31:58.000000Z",
    "created_at": "2024-03-29T03:31:58.000000Z",
    "id": 2
}
```
- **Respuesta de Error (Código 400):**
```
{
    "message": "The name field is required. (and 2 more errors)",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```
### 4. Logout
- **URL:** `/api/logout`
- **Método:** `POST`
- **Descripción:** Cierra la sesion del usuario.
- **Parámetros de Solicitud:**
 - Se configura en los header de postman con el nombre de **Authorization** y su valor lleva `Bearer {token}` donde {token} es el JWT
- **Respuesta Exitosa (Código 201):** CREATED
```
{
     "message": "session ended"
}
```

### 5. Registro de customer
- **URL:** `/api/customer/create`
- **Método:** `POST`
- **Descripción:** Registra un nuevo cliente en el sistema.
- **Parámetros de Solicitud:**
  - `dni`: Documento de identidad del cliente (cadena, obligatorio)
  - `name`: Nombre del cliente (cadena, obligatorio)
  - `las_name`: Apellido del cliente (cadena, obligatorio)
  - `email`: Correo electrónico del cliente (cadena, obligatorio, unico)
  - `region_id`: ID de la región del cliente (entero, obligatorio)
  - `commune_id`: ID de la comuna del cliente (entero, obligatorio)
  - `address`: Dirección del cliente (cadena, opcional)
- **Respuesta Exitosa (Código 201):** CREATED
```
{
    "message": "The customer has been successfully registered"
}
```

### 1. Mostrar customer por email o dni
- **URL:** `/api/customer/show`
- **Método:** `GET`
- **Descripción:** Busca la informacion de un cliente tomando el correo o el dni como referencia.
- **Parámetros de Solicitud:**
  - `sear`: Informacion del cliente (cadena, obligatorio)
- **Respuesta Exitosa (Código 200):** OK
```
{
    "customerData": [
        {
            "name": "andres",
            "last_name": "oberto",
            "address": "montalban",
            "region": "Andalucía",
            "commune": "Córdoba"
        }
    ]
}
```

### 1. Eliminar usuario
- **URL:** `/api/customer/destroy`
- **Método:** `DELETE`
- **Descripción:** Elimina a un cliente en el sistema.
- **Parámetros de Solicitud:**
  - `dni`: Documento de identidad del cliente (cadena, obligatorio)
- **Respuesta Exitosa (Código 200):** OK
```
{
    "customerData": {
        "dni": "v156428",
        "id_reg": 1,
        "id_com": 5,
        "email": "prueba3@gmail.com",
        "name": "andres",
        "last_name": "oberto",
        "address": "montalban",
        "date_reg": "2024-03-29 05:28:24",
        "status": "Trash"
    }
}
```
