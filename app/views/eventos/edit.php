<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3>✏️ Editar Evento</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/?url=eventos/update">
                        <input type="hidden" name="id" value="<?= $evento['id'] ?>">

                        <div class="form-group">
                            <label for="nombre">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= htmlspecialchars($evento['nombre']) ?>"
                                   required
                                   maxlength="255">
                            <small class="form-text text-muted">Ingrese el nombre del evento</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha y Hora de Inicio <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="fecha_inicio" 
                                           name="fecha_inicio" 
                                           value="<?= date('Y-m-d\TH:i', strtotime($evento['fecha_inicio'])) ?>"
                                           required>
                                    <small class="form-text text-muted">Fecha y hora de inicio del evento</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_termino">Fecha y Hora de Término <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="fecha_termino" 
                                           name="fecha_termino" 
                                           value="<?= date('Y-m-d\TH:i', strtotime($evento['fecha_termino'])) ?>"
                                           required>
                                    <small class="form-text text-muted">Fecha y hora de término del evento</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lugar">Lugar <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="lugar" 
                                   name="lugar" 
                                   value="<?= htmlspecialchars($evento['lugar']) ?>"
                                   required
                                   maxlength="255">
                            <small class="form-text text-muted">Lugar donde se realizará el evento</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                💾 Guardar Cambios
                            </button>
                            <a href="/?url=eventos/view&id=<?= $evento['id'] ?>" class="btn btn-secondary">
                                ❌ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validar que fecha_termino sea posterior a fecha_inicio
document.querySelector('form').addEventListener('submit', function(e) {
    const inicio = document.getElementById('fecha_inicio').value;
    const termino = document.getElementById('fecha_termino').value;
    
    if (inicio && termino && new Date(termino) < new Date(inicio)) {
        e.preventDefault();
        alert('La fecha de término debe ser posterior a la fecha de inicio');
        return false;
    }
});
</script>
