<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Solo administradores pueden ver esta página
if (!isset($_SESSION['user_role_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: ' . APP_URL . '/?url=home');
    exit();
}

$isAdmin = true;
?>

<div class="card">
    <div class="page-header">
        <h1>👥 Gestión de Usuarios</h1>
    </div>

    <!-- Barra de búsqueda y acciones -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem;">
        <input type="text" 
               id="searchInput" 
               placeholder="🔍 Buscar por nombre, email o rol..." 
               style="flex: 0 0 50%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
               oninput="filterTable()">
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?php echo APP_URL; ?>/?url=audit" class="btn btn-secondary">📋 Ver Auditoría</a>
            <a href="<?php echo APP_URL; ?>/?url=users/create" class="btn btn-success">➕ Crear Usuario</a>
        </div>
    </div>

    <?php if (count($users) > 0): ?>
    
    <style>
        .users-grid {
            display: grid;
            grid-template-columns: 
                minmax(40px, 60px)      /* # */
                minmax(150px, 1fr)      /* Nombre */
                minmax(180px, 1.5fr)    /* Email */
                minmax(100px, 0.8fr)    /* Rol */
                minmax(110px, 0.9fr)    /* Autenticación */
                minmax(120px, 1fr)      /* Último Acceso */
                minmax(80px, 0.6fr)     /* Estado */
                minmax(100px, 0.7fr);   /* Acciones */
            gap: 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            width: 100%;
        }
        .users-grid-header {
            display: contents;
        }
        .users-grid-header > div {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 2px solid #fff;
        }
        .users-grid-row {
            display: contents;
            cursor: pointer;
        }
        .users-grid-row > div {
            padding: 0.75rem 0.5rem;
            border-bottom: 1px solid #f0f0f0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .users-grid-row:hover > div {
            background-color: #e3f2fd;
        }
        .text-center {
            text-align: center;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.2s ease;
            margin: 0 0.15rem;
        }
        .btn-edit {
            background: #e3f2fd;
            color: #1976d2;
        }
        .btn-edit:hover {
            background: #1976d2;
            color: white;
            transform: scale(1.1);
        }
        .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }
        .btn-delete:hover {
            background: #d32f2f;
            color: white;
            transform: scale(1.1);
        }
    </style>

    <div class="users-grid">
        <!-- Header -->
        <div class="users-grid-header">
            <div>#</div>
            <div>Nombre</div>
            <div>Email</div>
            <div>Rol</div>
            <div>Autenticación</div>
            <div>Último Acceso</div>
            <div>Estado</div>
            <div class="text-center">Acciones</div>
        </div>
        
        <!-- Rows -->
        <?php 
        $contador = 1;
        foreach ($users as $user): 
        ?>
        <div class="users-grid-row" 
             ondblclick="window.location.href='<?php echo APP_URL; ?>/?url=users/view&id=<?php echo $user['id']; ?>'" 
             title="Doble click para ver detalles"
             data-id="<?php echo $user['id']; ?>"
             data-nombre="<?php echo htmlspecialchars($user['name']); ?>"
             data-email="<?php echo htmlspecialchars($user['email']); ?>"
             data-rol="<?php echo $user['role_nombre'] ?? 'Sin rol'; ?>"
             data-auth="<?php echo $user['auth_type']; ?>"
             data-estado="<?php echo $user['estado']; ?>">
            <div class="row-numero"><?php echo $contador++; ?></div>
            <div title="<?php echo htmlspecialchars($user['name']); ?>"><?php echo htmlspecialchars($user['name']); ?></div>
            <div title="<?php echo htmlspecialchars($user['email']); ?>"><?php echo htmlspecialchars($user['email']); ?></div>
            <div>
                <span class="badge badge-primary">
                    <?php echo $user['role_nombre'] ?? 'Sin rol'; ?>
                </span>
            </div>
            <div>
                <?php if ($user['auth_type'] === 'google'): ?>
                <span class="badge badge-danger">🔴 Google</span>
                <?php else: ?>
                <span class="badge badge-secondary">🔒 Local</span>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($user['last_login']): ?>
                    <small><?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?></small>
                <?php else: ?>
                    <em>Nunca</em>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($user['estado'] === 'activo'): ?>
                <span class="badge badge-success">✓ Activo</span>
                <?php else: ?>
                <span class="badge badge-warning">✗ Inactivo</span>
                <?php endif; ?>
            </div>
            <div class="text-center" style="white-space: normal;">
                <a href="<?php echo APP_URL; ?>/?url=users/edit&id=<?php echo $user['id']; ?>" 
                   class="btn-action btn-edit" title="Editar">✏️</a>
                <a href="<?php echo APP_URL; ?>/?url=users/delete&id=<?php echo $user['id']; ?>" 
                   class="btn-action btn-delete" title="Eliminar"
                   onclick="return confirm('¿Eliminar usuario <?php echo htmlspecialchars($user['name']); ?>?')">🗑️</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Control de paginación -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding: 1rem; background-color: #f8f9fa; border-radius: 4px;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <label for="recordsPerPage" style="font-weight: 500;">Mostrar:</label>
            <select id="recordsPerPage" onchange="updateRecordsPerPage()" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                <option value="10">10 registros</option>
                <option value="20" selected>20 registros</option>
                <option value="50">50 registros</option>
                <option value="100">100 registros</option>
                <option value="999999">Todos</option>
            </select>
        </div>
        <div id="paginationControls" style="display: flex; align-items: center; gap: 0.5rem;">
            <!-- Pagination buttons will be inserted here -->
        </div>
        <div id="recordsInfo" style="color: #666; font-size: 0.9rem;">
            Mostrando <span id="showingStart">0</span>-<span id="showingEnd">0</span> de <span id="totalCount">0</span> usuarios
        </div>
    </div>

    <script>
    let currentPage = 1;
    let recordsPerPage = 20;

    // Initialize on load
    window.addEventListener('DOMContentLoaded', function() {
        updateDisplay();
    });

    function filterTable() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const rows = Array.from(document.querySelectorAll('.users-grid-row'));
        
        rows.forEach(row => {
            const nombre = row.getAttribute('data-nombre').toLowerCase();
            const email = row.getAttribute('data-email').toLowerCase();
            const rol = row.getAttribute('data-rol').toLowerCase();
            
            const matches = nombre.includes(searchValue) || 
                          email.includes(searchValue) || 
                          rol.includes(searchValue);
            
            if (matches || searchValue === '') {
                row.setAttribute('data-visible', 'true');
            } else {
                row.setAttribute('data-visible', 'false');
            }
        });
        
        currentPage = 1;
        updateDisplay();
    }

    function updateRecordsPerPage() {
        recordsPerPage = parseInt(document.getElementById('recordsPerPage').value);
        currentPage = 1;
        updateDisplay();
    }

    function updateDisplay() {
        const rows = Array.from(document.querySelectorAll('.users-grid-row'));
        
        // Get visible rows based on filter
        const allRows = rows.filter(row => {
            const visible = row.getAttribute('data-visible');
            return visible === null || visible === 'true';
        });
        
        const totalRecords = allRows.length;
        
        // Calculate total pages
        const totalPages = Math.ceil(totalRecords / recordsPerPage);
        
        // Ensure current page is valid
        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }
        if (currentPage < 1) {
            currentPage = 1;
        }
        
        // Hide all rows first
        rows.forEach(row => row.style.display = 'none');
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = Math.min(startIndex + recordsPerPage, totalRecords);
        
        // Show only records for current page
        for (let i = startIndex; i < endIndex; i++) {
            if (allRows[i]) {
                allRows[i].style.display = 'contents';
            }
        }
        
        // Update row numbers for visible rows
        const displayedRows = allRows.slice(startIndex, endIndex);
        displayedRows.forEach((row, index) => {
            const numeroEl = row.querySelector('.row-numero');
            if (numeroEl) {
                numeroEl.textContent = startIndex + index + 1;
            }
        });
        
        // Update info text
        document.getElementById('showingStart').textContent = totalRecords > 0 ? startIndex + 1 : 0;
        document.getElementById('showingEnd').textContent = endIndex;
        document.getElementById('totalCount').textContent = totalRecords;
        
        // Update pagination controls
        updatePaginationControls(totalPages);
    }

    function updatePaginationControls(totalPages) {
        const paginationDiv = document.getElementById('paginationControls');
        
        if (totalPages <= 1) {
            paginationDiv.innerHTML = '';
            return;
        }
        
        let html = '';
        
        // Previous button
        html += `<button onclick="goToPage(${currentPage - 1})" 
                 ${currentPage === 1 ? 'disabled' : ''} 
                 style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer; ${currentPage === 1 ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
                 ← Anterior
                 </button>`;
        
        // Page numbers
        const maxButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);
        
        if (endPage - startPage < maxButtons - 1) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }
        
        if (startPage > 1) {
            html += `<button onclick="goToPage(1)" style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">1</button>`;
            if (startPage > 2) {
                html += `<span style="padding: 0.4rem;">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === currentPage;
            html += `<button onclick="goToPage(${i})" 
                     style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: ${isActive ? 'var(--primary-color)' : 'white'}; color: ${isActive ? 'white' : '#333'}; border-radius: 4px; cursor: pointer; font-weight: ${isActive ? '600' : '400'};">
                     ${i}
                     </button>`;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span style="padding: 0.4rem;">...</span>`;
            }
            html += `<button onclick="goToPage(${totalPages})" style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">${totalPages}</button>`;
        }
        
        // Next button
        html += `<button onclick="goToPage(${currentPage + 1})" 
                 ${currentPage === totalPages ? 'disabled' : ''} 
                 style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer; ${currentPage === totalPages ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
                 Siguiente →
                 </button>`;
        
        paginationDiv.innerHTML = html;
    }

    function goToPage(page) {
        currentPage = page;
        updateDisplay();
    }
    </script>
        </tbody>
    </table>

    <?php else: ?>
    <div class="alerta alerta-info">
        ℹ️ No hay usuarios registrados en el sistema.
    </div>
    <?php endif; ?>
</div>
