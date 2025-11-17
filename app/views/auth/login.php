<?php
// Vista: formulario de login con opciones de autenticación local y Google
?>
<div class="login-container">
    <div class="login-box">
        <h1>Bienvenido</h1>
        <p class="subtitle">Sistema de Acreditación</p>

        <?php if (!empty($error)): ?>
            <div class="alerta alerta-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login local -->
        <form method="post" action="<?php echo APP_URL; ?>/?url=auth/login">
            <div class="form-group">
                <label for="email">📧 Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="tu@correo.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">🔐 Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Ingresar
            </button>
        </form>

        <!-- Separador -->
        <div class="login-divider">o continúa con</div>

        <!-- Login con Google -->
        <a href="<?php echo APP_URL; ?>/?url=auth/google-login" class="google-btn">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 48 48'%3E%3Cpath fill='%23EA4335' d='M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.9C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l8.02 6.21c1.91-5.22 7.02-9.03 13.42-9.03z'/%3E%3Cpath fill='%2334A853' d='M46.98 24.55c0-1.6-.15-3.09-.38-4.55H24v8.5h12.94c-.58 2.96-2.26 5.48-4.69 7.07l7.73 5.95c4.49-4.14 7.28-10.24 7.28-17.07z'/%3E%3Cpath fill='%234285F4' d='M10.88 28.75c-.85 2.41-1.37 5.05-1.37 7.75 0 2.7.52 5.34 1.37 7.75l-8.03 6.22C1.91 41.49 0 33.38 0 24c0-9.38 1.91-17.49 5.85-23.47l8.03 6.22z'/%3E%3Cpath fill='%23FBBC04' d='M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-5.95c-2.15 1.45-4.92 2.3-8.16 2.3-6.4 0-11.51-3.81-13.42-9.03l-8.02 6.21C6.51 42.62 14.62 48 24 48z'/%3E%3Cpath fill='none' d='M0 0h48v48H0z'/%3E%3C/svg%3E" alt="Google">
            Continuar con Google
        </a>
    </div>
</div>
