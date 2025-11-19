<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo APP_URL; ?>/assets/favicon.svg">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <script src="<?php echo APP_URL; ?>/assets/js/main.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                🎓 <?php echo APP_NAME; ?>
            </div>
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                ☰
            </button>
            <div class="menu" id="mainMenu">
                <a href="<?php echo APP_URL; ?>">Inicio</a>
                <a href="<?php echo APP_URL; ?>/?url=home/about">Acerca de</a>
                <a href="<?php echo APP_URL; ?>/?url=instituciones">Instituciones</a>
                <a href="<?php echo APP_URL; ?>/?url=participantes">Participantes</a>
                <a href="<?php echo APP_URL; ?>/?url=eventos">Eventos</a>
                <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu-dropdown">
                        <a href="#" class="user-email" onclick="toggleUserMenu(event)">
                            👤 <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Usuario'); ?>
                        </a>
                        <div class="dropdown-content" id="userDropdown">
                            <a href="<?php echo APP_URL; ?>/?url=users">Gestión de Usuarios</a>
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
    function toggleMobileMenu() {
        const menu = document.getElementById('mainMenu');
        menu.classList.toggle('show');
    }
    
    function toggleUserMenu(event) {
        event.preventDefault();
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('show');
    }
    
    // Cerrar menús si se hace clic fuera de ellos
    window.onclick = function(event) {
        if (!event.target.matches('.user-email') && !event.target.matches('.mobile-menu-toggle')) {
            const dropdown = document.getElementById('userDropdown');
            const mobileMenu = document.getElementById('mainMenu');
            
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
            
            // Solo cerrar menú móvil en pantallas pequeñas
            if (window.innerWidth <= 768 && mobileMenu && mobileMenu.classList.contains('show')) {
                mobileMenu.classList.remove('show');
            }
        }
    }
    
    // Cerrar menú móvil al hacer clic en un enlace
    document.addEventListener('DOMContentLoaded', function() {
        const menuLinks = document.querySelectorAll('.menu > a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    const menu = document.getElementById('mainMenu');
                    if (menu) {
                        menu.classList.remove('show');
                    }
                }
            });
        });
    });
    </script>
    
    <div class="page-wrapper">
        <div class="container">
