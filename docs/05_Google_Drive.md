# 5. Estrategia de Almacenamiento con Google Drive

Es costoso y complejo guardar los archivos (ej. imágenes de perfil, documentos, copias de seguridad de Postgres) directamente en el VPS local, comprometiendo la escalabilidad. Larastack integra Google Drive nativamente como sistema primario (o backup) aprovechando el contrato `Illuminate\Contracts\Filesystem\Filesystem`.

## Paquete Propuesto
Se utiliza el community standard `masbug/flysystem-google-drive-ext` con el FileSystem nativo de Laravel.  
Configuraremos el archivo `config/filesystems.php` definiendo el disco "google".

## Flujo de Seguridad y Protección (Zero Trust Storage)
La clave del manejo centralizado en una arquitectura limpia es NUNCA exponer el File ID o enlace del Storage a la base de datos de los clientes sin protección de autenticación.

1. **Subida de Archivos:**
   - El frontend Vue 3 llama via API a un `FileStoreRequest`.
   - El controller delega a `StorageService` la obligación de validaciones MIME, peso con optimizaciones y luego invoca: `$path = Storage::disk('google')->put('/', $request->file('documento'));`. Se guarda el ID único en Postgres.
2. **Acceso Seguro (Lectura) vía Laravel y Spatie:**
   - Cuando Vue requiere mostrar o descargar el recurso, NO consulta GDrive. Consulta nuestra API: `GET /api/v1/files/download/{id_interno_db}`.
   - El Controller verifica con `Policies` si el usuario tiene permiso (ej. dueño del archivo o rol ADMIN según Spatie).
   - En caso positivo, el API devuelve una respuesta de Stream desde Google Drive al usuario, manteniendo el archivo confidencial invisible en la web pública.
