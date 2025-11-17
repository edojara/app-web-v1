# Autenticación con Google OAuth2

## Configuración de Google OAuth2

Para usar autenticación con Google, necesitas:

1. **Crear aplicación en Google Cloud Console:**
   - Ve a https://console.cloud.google.com
   - Crea un nuevo proyecto
   - Habilita "Google+ API"
   - Ve a "Credenciales"
   - Crea credenciales de tipo "Aplicación Web (OAuth 2.0)"

2. **Configurar URIs autorizados:**
   - Autoridades de JavaScript: `http://acreditacion.grupoeducar.cl`
   - URI de redirección autorizados: `http://acreditacion.grupoeducar.cl/?url=auth/google-callback`

3. **Obtener credenciales:**
   - ID de cliente (Client ID)
   - Secreto de cliente (Client Secret)

4. **Configurar en la aplicación:**
   - Define las variables en tu servidor (.env o variable de entorno):
     ```bash
     export GOOGLE_CLIENT_ID="tu-client-id.apps.googleusercontent.com"
     export GOOGLE_CLIENT_SECRET="tu-client-secret"
     ```
   - O edita directamente `config/google-oauth.php`

5. **Ejecutar migración de BD:**
   ```bash
   mysql app_web_db < migrate_google_oauth.sql
   ```

## Características

- **Dos tipos de autenticación:**
  - Cuenta local (usuario/contraseña)
  - Google OAuth2

- **Tabla de usuarios actualizada:**
  - `auth_type`: tipo de autenticación (local/google)
  - `google_id`: ID de Google para usuarios OAuth2
  - `password`: nullable para usuarios con OAuth2

- **Seguridad:**
  - CSRF protection con state tokens
  - Validación de estado de usuario
  - Creación automática de usuario en primer login con Google

## Flujo de autenticación con Google

1. Usuario hace clic en "Iniciar con Google"
2. Se genera state token y se redirige a Google
3. Usuario autoriza la aplicación en Google
4. Google redirige con código de autorización
5. App intercambia código por access token
6. App obtiene información de usuario
7. Se busca o crea el usuario en BD
8. Se inicia sesión automáticamente

