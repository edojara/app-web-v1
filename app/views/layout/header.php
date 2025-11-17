<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo APP_URL; ?>/assets/favicon.svg">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                🎓 <?php echo APP_NAME; ?>
            </div>
            <div class="menu">
                <a href="<?php echo APP_URL; ?>">Inicio</a>
                <a href="<?php echo APP_URL; ?>/?url=home/about">Acerca de</a>
                <a href="<?php echo APP_URL; ?>/?url=users">Usuarios</a>
                <a href="<?php echo APP_URL; ?>/?url=instituciones">Instituciones</a>
                <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu-dropdown">
                        <a href="#" class="user-email" onclick="toggleUserMenu(event)">
                            👤 <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Usuario'); ?>
                        </a>
                        <div class="dropdown-content" id="userDropdown">
                            <a href="<?php echo APP_URL; ?>/?url=profile">Mi Perfil</a>
                            <a href="<?php echo APP_URL; ?>/?url=auth/logout">Salir</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo APP_URL; ?>/?url=auth/login">Ingresar</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    <script>
    function toggleUserMenu(event) {
        event.preventDefault();
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('show');
    }
    
    // Cerrar el dropdown si se hace clic fuera de él
    window.onclick = function(event) {
        if (!event.target.matches('.user-email')) {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
    </script>
    
    <div class="page-wrapper">
        <div class="container">
