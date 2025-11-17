<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        
        header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #3498db;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        main {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div>
                <strong><?php echo APP_NAME; ?></strong>
            </div>
            <div>
                <a href="<?php echo APP_URL; ?>">Inicio</a>
                <a href="<?php echo APP_URL; ?>/?url=home/about">Acerca de</a>
                <a href="<?php echo APP_URL; ?>/?url=users">Usuarios</a>
                <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="color: #fff; margin-left: 0.8rem;">|</span>
                    <a href="<?php echo APP_URL; ?>/?url=profile">Mi Perfil</a>
                    <span style="color: #fff; margin-left: 0.8rem;">|</span>
                    <a href="<?php echo APP_URL; ?>/?url=auth/logout">Cerrar sesión (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a>
                <?php else: ?>
                    <a href="<?php echo APP_URL; ?>/?url=auth/login">Ingresar</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    <div class="container">
        <main>
