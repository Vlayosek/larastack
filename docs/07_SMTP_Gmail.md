# 7. Configuración de Correo SMTP vía Gmail

Para notificaciones transaccionales (registro, reseteo de contraseñas, confirmaciones clave) es esencial enlazar correctamente el servicio. Siendo un skeleton o una app naciente, el SMTP de Gmail corporativo/personal es ideal y robusto.

## Requisitos Críticos de Seguridad en Google Workspace / Gmail
La autenticación básica de usuario/clave plana ha sido de-precada por política global de Google (`Less Secure Apps` está apagado globalmente). Para enviar SMS usaremos **App Passwords / Contraseñas de Aplicación**.

1. Habilite la Verificación en Dos Pasos (2FA) en la cuenta emisora en la interfaz Google "Seguridad".
2. Navegue a la sección "Contraseñas de Aplicación".
3. Genere una nueva con el nombre de Referencia "API Larastack Boilerplate". Resultará en un token alfanumérico aleatorio (Ej: `abcd efgh ijkl mnop`).

## Configuración de Variables (En .env local o Entorno CI CI/CD)
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=tucorreo_app@gmail.com
# 🚨 Quita los espacios vacíos de la contraseña API:
MAIL_PASSWORD=abcdefghijklmnop     
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tucorreo_app@gmail.com
MAIL_FROM_NAME="${APP_NAME} Sistema Central"
```

## Reglas Obligatorias en Desarrollo de Correos
1. **Asincronismo:** Los correos Gmail pueden tomar de 1.5 a 3 segundos bloqueando el proceso principal (bloqueando el loading del usuario en pantalla de Login o Registro en VUE). 
2. Todo mailable en Laravel implementará `implements ShouldQueue` instanciando la persistencia en nuestro contenedor de Colas (Redis).
3. Monitoreado en producción (Opcional, implementando Laravel Horizon bajo contraseña).
