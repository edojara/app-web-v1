# Sistema de Gestión de Usuarios con Roles

## Cambios Realizados

1. **Modelo Role.php** - Gestiona los 4 perfiles:
   - Administrador
   - Lector
   - Acreditador
   - Informes

2. **Actualizado User.php** - Nuevos métodos:
   - getAllWithRoles()
   - getWithRole()
   - update()
   - delete()

3. **Controlador UsersController.php** - Operaciones CRUD:
   - index() - Listar usuarios
   - view() - Ver detalle
   - create() - Crear usuario
   - edit() - Editar usuario
   - delete() - Eliminar usuario

4. **Vistas de Usuarios**:
   - index.php - Tabla de usuarios con acciones
   - view.php - Detalle de usuario
   - create.php - Formulario crear usuario
   - edit.php - Formulario editar usuario

5. **Script de Base de Datos** - database_users.sql

## Pasos para Sincronizar

### 1. En tu máquina local, sincroniza los cambios:

```bash
cd /home/edo/Documentos/acreditacion/app-web-v1

# Agregar cambios
git add .

# Hacer commit
git commit -m "Agregar sistema de gestión de usuarios con 4 perfiles"

# Hacer push a GitHub
git push origin main
```

### 2. En el servidor web, actualiza la base de datos:

```bash
ssh -i ~/.ssh/id_servidor edo@192.168.100.222

# Ir al directorio de la aplicación
cd /var/www/html/app-web-v1

# Ejecutar el script SQL (como sudo)
sudo mysql < database_users.sql

# Verificar que los cambios se aplicaron
sudo mysql -u app_user -p'app_password_2025' app_web_db -e "SHOW TABLES; SELECT * FROM roles; SELECT * FROM users;"

# Volver a traer los cambios de código
git pull origin main

# Restablecer permisos
sudo chown -R www-data:www-data . && sudo chmod -R 755 .

# Salir del servidor
exit
```

### 3. Acceder a tu aplicación:

```
http://192.168.100.222/index.php?url=users
```

## URLs Disponibles

- `/index.php?url=users` - Listar usuarios
- `/index.php?url=users/view&id=1` - Ver detalle de usuario
- `/index.php?url=users/create` - Crear nuevo usuario
- `/index.php?url=users/edit&id=1` - Editar usuario
- `/index.php?url=users/delete&id=1` - Eliminar usuario

## Base de Datos

Tabla `roles` con 4 perfiles:
1. administrador - Acceso total a la plataforma
2. lector - Puede visualizar información y reportes
3. acreditador - Puede acreditar y validar información
4. informes - Acceso a generación y visualización de informes

Tabla `users` actualizada con:
- role_id (FK a roles)
- estado (activo/inactivo)
