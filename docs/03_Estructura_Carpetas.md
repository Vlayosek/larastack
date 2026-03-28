# 3. Estructura de Carpetas (Arquitectura Separada)

Se ha configurado el proyecto en una arquitectura completamente desacoplada (Decoupled), dividiendo estrictamente el backend del frontend en carpetas separadas.

```text
larastack-admin/
├── backend/                    # LARAVEL 12 API (PHP 8.3)
│   ├── app/
│   │   ├── Http/Controllers/   # Controladores API para devolver respuetas JSON tipadas
│   │   ├── Repositories/       # Capa de Acceso a Base de Datos
│   │   ├── Services/           # Servicios inyectables (Logica de Negocio, ej. QrGeneratorService)
│   │   ├── DTOs/               # Objetos Inmutables interceptores
│   │   └── Models/             # Eloquent Models
│   ├── routes/api.php          # Punto central de entrada API protegido por Sanctum
│   ├── config/cors.php         # CORS abierto para recibir a frontend
│   └── artisan
├── frontend/                   # VUE 3 + VITE (Node)
│   ├── src/
│   │   ├── assets/             # Estilos genericos y globales (Tailwind)
│   │   ├── components/         # Botones, Tablas genéricas UI
│   │   ├── views/              # Vistas completas por página
│   │   ├── stores/             # Pinia State Management
│   │   └── router/             # Vue Router SPA local
│   ├── vite.config.js          # Config Vite
│   └── package.json
├── docker/                     # Infraestructura global
│   ├── php/Dockerfile
│   └── nginx/default.conf
└── docker-compose.yml          # Container orchestration root
```

## Beneficios de Separación
1. **Escalabilidad Física**: El frontend puede publicarse en CDN de borde (ej. Vercel, Netlify o AWS S3/Cloudfront) por centavos, mitigando carga al VPS PHP.
2. **Ecosistema Puro**: El Frontend no depende ni contamina a Composer o Laravel Vite Plugin. Es un proceso SPA de NodeJS y Nginx puro consumiendo un endpoint `/api/v1`.
3. **Equipos de Desarrollo Delineados**: Los Frontend devs no requieren levantar PHP localmente si el backend está en un server test (se conectan cambiando un archivo `.env` apuntando al backend staging).
