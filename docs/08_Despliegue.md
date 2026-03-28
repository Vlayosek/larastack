# 8. Estrategia de Despliegue en Producción (CI/CD DevOps)

Se recomienda encarecidamente la estrategia **Docker-Compose multi-node o VPS Single Node (Linode / DO / Hetzner).** Se rechaza el despliegue manual o copia por FTP, priorizando un pipeline limpio.

## Fases y Entornos del Pipeline Sugerido

Utilizando Github Actions o Gitlab CI:

### 1. Gestión de Entornos (`Secret Managers`)
No envíe jamás su configuración `.env` al repositorio maestro o staging. El servidor/VPS debe mantener su `.env` maestro configurado manualmente por SSH la primera vez. Variables seguras también deben guardarse y sincronizarse por CI o Vault (como doppler).

### 2. Pipeline Test-Before-Deploy
Cuando Master/Main recibe actualizaciones directas:
- Levanta contenedor efímero (`ubuntu:latest`)
- Instala dependencias (`npm ci`, `composer install`)
- Corre validadores de código (`PHPStan` / Lint / Pest), y pruebas unitarias aisladas.

### 3. Embalaje
- Construimos la imagen y los assets de Frontend `npm run build` en el pipeline usando el Dockerfile de Producción (distinto al local con hot-reload desactivado y vendor incluido). Se pushea la imagen final al Container Registry (DockerHub / Github Registry).

### 4. Zero Downtime en Entorno Producción (Blue-Green / Envoyer)
Mientras se empaqueta de fondo, el servidor:
- **Descarga la Imagen Pre-Buildeada**: Hace pull rapidísimo.
- **Base de Datos**: Corre rutinas automatizadas `php artisan migrate --force`. (La arquitectura debe asegurar que las migraciones son aditivas y NO Borran/Mutan datos vitales directamente sin un step extra que puede destruir las vistas viejas hasta que se libere el código nuevo).
- **Liberación de Caché**: Ejecuta `php artisan optimize:clear` y `php artisan route:cache` `config:cache`.
- Recreará/Reiniciará suavemente los contendores `docker compose up -d` solo regenerando los hashes modificados del container. 

### Sistema de Prevención (Monitoring Vital)
Es fundamental en la puesta en nube incluir `Sentry` (`composer require sentry/sentry-laravel`) enlazándolo a un Slack u webhook propio, asegurando el control global real-time desde el día uno del proyecto al detectarse una Exception 500 no tratada en capa Controladores en vivo.
