<h1>Acerca de <?php echo APP_NAME; ?></h1>

<p>Esta aplicación web fue creada utilizando la arquitectura LAMP:</p>

<ul style="margin-left: 2rem; margin-top: 1rem;">
    <li><strong>Linux:</strong> Sistema operativo</li>
    <li><strong>Apache:</strong> Servidor web</li>
    <li><strong>MySQL:</strong> Sistema de gestión de base de datos</li>
    <li><strong>PHP:</strong> Lenguaje de programación del lado del servidor</li>
</ul>

<h2 style="margin-top: 2rem;">Estructura del Proyecto</h2>
<pre style="background-color: #f5f5f5; padding: 1rem; border-radius: 4px; overflow-x: auto; margin-top: 1rem;">
app-web-v1/
├── app/
│   ├── controllers/     # Controladores
│   ├── models/          # Modelos de datos
│   └── views/           # Vistas (HTML)
├── config/
│   ├── config.php       # Configuración general
│   └── database.php     # Configuración de BD
├── public/
│   ├── index.php        # Punto de entrada
│   └── .htaccess        # Reescritura de URLs
└── README.md
</pre>

<h2 style="margin-top: 2rem;">Tecnologías</h2>
<ul style="margin-left: 2rem; margin-top: 1rem;">
    <li>PHP 7.4+</li>
    <li>MySQL 5.7+</li>
    <li>HTML5</li>
    <li>CSS3</li>
</ul>

<p style="margin-top: 2rem; font-size: 0.9rem; color: #666;">Versión <?php echo APP_VERSION; ?></p>
