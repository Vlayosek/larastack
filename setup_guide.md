# Guía de Configuración y Desarrollo - Larastack Admin

Esta guía detalla los pasos necesarios para levantar el proyecto desde cero y el flujo de trabajo para realizar cambios.

## 1. Instalación Inicial

Si acabas de clonar el repositorio, sigue estos pasos:

### Configuración del Entorno
1. Copia el archivo de ejemplo de variables de entorno:
   ```bash
   cp backend/.env.example backend/.env
   ```
2. Asegúrate de que las variables de base de datos coincidan con las de `docker-compose.yml`.

### Levantar Docker
1. Construye y levanta los contenedores:
   ```bash
   docker compose up -d --build
   ```

### Configuración de Laravel (Backend)
1. Instalar dependencias de PHP y generar la clave de la aplicación:
   ```bash
   docker exec larastack_app composer install
   docker exec larastack_app php artisan key:generate
   ```
2. Ejecutar las migraciones y seeders para tener datos base (usuarios y roles):
   ```bash
   docker exec larastack_app php artisan migrate:fresh --seed
   ```

### Configuración de Vue (Frontend)
El contenedor de frontend instala las dependencias automáticamente al iniciar. Puedes ver el estado con:
```bash
docker logs -f larastack_frontend
```

---

## 2. Acceso al Sistema

Una vez levantado todo, puedes acceder en:
- **Frontend**: [http://localhost:5173](http://localhost:5173)
- **API Backend**: [http://localhost:8082](http://localhost:8082)

**Credenciales Maestras:**
- **Email**: `admin@larastack.com`
- **Password**: `password`

---

## 3. Flujo de Desarrollo

### Realizar Cambios en el Backend
- Los archivos se sincronizan automáticamente con el contenedor.
- Si creas una nueva migración: `docker exec larastack_app php artisan make:migration <nombre>`
- Para ejecutar migraciones: `docker exec larastack_app php artisan migrate`

### Realizar Cambios en el Frontend
- El HMR (Hot Module Replacement) está activo. Los cambios en `.vue` o `.js` se ven reflejados al instante en el navegador.

### Comandos Útiles
- **Ver Logs**: `docker compose logs -f [servicio]` (servicios: app, web, frontend, db)
- **Acceder a la terminal de PHP**: `docker exec -it larastack_app sh`
- **Limpiar Caché**: `docker exec larastack_app php artisan optimize:clear`
