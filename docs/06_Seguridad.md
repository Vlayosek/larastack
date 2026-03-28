# 6. Estrategia de Seguridad (OWASP & Mejores Prácticas)

Larastack abraza los esquemas de mitigación contra vulnerabilidades web adoptando los lineamientos de OWASP resolviéndolos orgánicamente dentro de Laravel + Vue 3.

## Protecciones Incluidas en la Base

1. **Broken Authentication & Session Management:**
   - **Manejo:** Laravel Sanctum utiliza autenticación stateful (Cookies strict) validando tokens firmados o sesiones reales sin almacenar credencias temporales del cliente en local storage.
   - **Rate Limiting:** Todas las API de Auth (Login, Reset) y los envíos de OTP disponen de *Throttling* provisto por Laravel de IP/Correo: Máximo 5 intentos de fallo en login, bloqueando fuerza bruta.
   
2. **Cross-Site Request Forgery (CSRF):**
   - **Manejo:** Frontend y Backend comparten origen. Axios en Vue recibe y transmite en cada petición POST, PUT o DELETE el `XSRF-TOKEN` enviado por el backend automáticamente en el bootstrap, haciéndolo impenetrable al inyectar solicitudes de origen falso.
   
3. **Cross-Site Scripting (XSS):**
   - **Manejo:** Evitado en Backend inyectando parámetros JSON strict, validando FormRequests. Evitado en Frontend en el motor virtual DOM, Vue pre-escapa strings. La regla fundamental en el skeleton para el equipo es **Jamás usar el atributo v-html** en inputs de usuarios.
   
4. **SQL Injection:**
   - **Manejo:** En capa Repository, estrictamente prohibido usar funciones como `DB::raw()` para inputs de búsqueda. Toda consulta compleja debe pasar primero de Query String a DTO sanitizado o bind values `where('x', ?)` embebidos en el Query Builder PDO.
   
5. **Insecure Direct Object References (IDOR):**
   - **Manejo:** Absolutamente todo acceso individual `GET /api/v1/projects/50` se intercepta utilizando **Laravel Policies** vinculado a los tokens o sesiones del usuario antes de tocar el Service.

## Headers de Seguridad Adicionales propuestos (Nginx / Nativos)
Se recomienda inyectar en bloque Server la siguiente seguridad HTTP:
- `X-Frame-Options: SAMEORIGIN` (Mitigación total al Clickjacking iframe, la SPA de Vue es la única que lo dibuja).
- `X-Content-Type-Options: nosniff` (Previene ataques basados en MIME-types).
- `Strict-Transport-Security: max-age=31536000` (HSTS: Sólo HTTPS).
