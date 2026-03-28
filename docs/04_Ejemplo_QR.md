# 4. Caso de Uso: Código QR Firmado Criptográficamente

## El Problema Frecuente (Broken Access Control)
En sistemas empresariales a veces emitimos documentos (ej. certificados, facturas, comprobantes) que llevan un código QR. Alguien externo necesita escanear el QR con su móvil para validar la "veracidad" del documento consultando nuestro API. 

¿Cómo evitamos que alguien malicioso manipule la información del QR o adivine IDs para consultar documentos `ID=5` o simplemente altere la data de `ID=6`?

## La Solución: Signed URLs
Utilizamos el componente de Laravel `URL::temporarySignedRoute`. Se inyecta la URL de verificación (con una validez estricta por tiempo si se desea) en un generador de QR. 

La URL resultante sería:
`https://app.tu-dominio.com/api/v1/verify-doc/5?expires=16900223&signature=bf464c4c235cbcd34a2...`

El hash de la variable secreta de firma se compila junto al ID en el motor de Laravel usando tu `APP_KEY`. Si el atacante altera el ID a `6`, la firma de MD5/SHA en la cadena cambia y Laravel devuelve inherentemente un **403 Invalid Signature** por el middleware nativo.

## Implementación Base
1. Crea tu capa de servicio inyectable (Revisa `app/Services/QrGeneratorService.php`).
2. Usa el middleware en tus rutas:
```php
Route::get('/api/v1/verify-doc/{document_id}', [DocumentController::class, 'verifyQr'])
    ->name('api.documents.verify')
    ->middleware('signed'); // Middleware de protección Criptográfica.
```
