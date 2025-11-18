<div class="container-fluid" style="max-width: 98%; padding: 0 1rem;">
    <div class="content-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; margin-top: 1.5rem;">
        <input 
            type="text" 
            id="searchInput" 
            class="form-control" 
            placeholder="Buscar por nombre, lugar..." 
            style="flex: 1; max-width: 50%;">
        <a href="/?url=eventos/create" class="btn btn-primary" style="white-space: nowrap;">
            ➕ Nuevo Evento
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <h2 style="margin-bottom: 1.5rem;">📅 Eventos</h2>

            <?php if (empty($eventos)): ?>
                <div class="text-center py-4">
                    <p class="text-muted">No hay eventos registrados</p>
                    <a href="/?url=eventos/create" class="btn btn-primary">Crear primer evento</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Nombre</th>
                                <th style="width: 180px;">Fecha Inicio</th>
                                <th style="width: 180px;">Fecha Término</th>
                                <th>Lugar</th>
                                <th style="width: 120px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $numero = 1; ?>
                            <?php foreach ($eventos as $evento): ?>
                                <tr ondblclick="window.location.href='/?url=eventos/view&id=<?= $evento['id'] ?>'" style="cursor: pointer;">
                                    <td><?= $numero++ ?></td>
                                    <td><strong><?= htmlspecialchars($evento['nombre']) ?></strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($evento['fecha_inicio'])) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($evento['fecha_termino'])) ?></td>
                                    <td><?= htmlspecialchars($evento['lugar']) ?></td>
                                    <td onclick="event.stopPropagation();" ondblclick="event.stopPropagation();">
                                        <a href="/?url=eventos/edit&id=<?= $evento['id'] ?>" 
                                           class="btn btn-sm btn-primary" 
                                           onclick="event.stopPropagation();"
                                           title="Editar">✏️</a>
                                        <a href="/?url=eventos/delete&id=<?= $evento['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="event.stopPropagation(); return confirm('¿Está seguro de eliminar este evento?')"
                                           title="Eliminar">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Filtrado en tiempo real
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Limpiar con Escape
document.getElementById('searchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        this.value = '';
        document.querySelectorAll('tbody tr').forEach(row => row.style.display = '');
        this.blur();
    }
});
</script>
