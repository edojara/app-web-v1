<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h3>📤 Importar Participantes desde CSV</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <h5>📋 Estructura del archivo CSV</h5>
                        <p>El archivo debe tener las siguientes columnas en este orden:</p>
                        <ol>
                            <li><strong>Nombre Completo</strong> (obligatorio) - Nombre completo del participante</li>
                            <li><strong>RUT</strong> (obligatorio) - RUT en formato XX.XXX.XXX-X</li>
                            <li><strong>Teléfono</strong> (opcional) - Número de contacto</li>
                            <li><strong>Email</strong> (opcional) - Correo electrónico</li>
                            <li><strong>Cargo</strong> (opcional) - Cargo o función del participante</li>
                            <li><strong>Institución</strong> (obligatorio) - Nombre exacto de la institución</li>
                        </ol>
                        <p class="mb-0"><strong>Nota:</strong> La primera fila debe contener los encabezados y será ignorada.</p>
                    </div>

                    <div class="alert alert-warning">
                        <h5>🏛️ Nombres de Instituciones Disponibles</h5>
                        <p class="mb-2"><small>Copie el nombre exactamente como aparece aquí:</small></p>
                        <div class="row">
                            <?php foreach (array_chunk($instituciones, ceil(count($instituciones) / 3)) as $chunk): ?>
                                <div class="col-md-4">
                                    <ul style="font-size: 0.9rem; line-height: 1.6;">
                                        <?php foreach ($chunk as $inst): ?>
                                            <?php if ($inst['estado'] === 'activa'): ?>
                                                <li><?= htmlspecialchars($inst['nombre']) ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="card mb-3" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5>📄 Ejemplo de archivo CSV:</h5>
                            <pre style="background: white; padding: 1rem; border-radius: 4px; font-size: 0.85rem;">Nombre Completo,RUT,Teléfono,Email,Cargo,Institución
Juan Pérez González,12.345.678-9,+56 9 1234 5678,jperez@ejemplo.cl,Profesor,Universidad de Chile
María Silva Torres,23.456.789-0,+56 9 8765 4321,msilva@ejemplo.cl,Directora,Pontificia Universidad Católica de Chile
Carlos Rojas Muñoz,34.567.890-1,,,Coordinador,Universidad de Santiago de Chile</pre>
                            <p class="mb-0"><small class="text-muted">Puede copiar este ejemplo y editarlo con sus datos.</small></p>
                        </div>
                    </div>

                    <form method="POST" action="/?url=participantes/processImport" enctype="multipart/form-data" id="importForm">
                        <div class="form-group">
                            <label for="csv_file">Seleccionar archivo CSV <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control-file" 
                                   id="csv_file" 
                                   name="csv_file" 
                                   accept=".csv"
                                   required>
                            <small class="form-text text-muted">Seleccione un archivo CSV con la estructura indicada arriba</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                📤 Importar Participantes
                            </button>
                            <a href="/?url=participantes" class="btn btn-secondary">
                                ❌ Cancelar
                            </a>
                            <a href="/?url=participantes/export" class="btn btn-success">
                                📥 Descargar Plantilla (CSV actual)
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>ℹ️ Información Importante</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Los RUTs deben ser únicos. Si un RUT ya existe, esa línea será omitida.</li>
                        <li>El nombre de la institución debe coincidir exactamente con uno de los nombres listados arriba.</li>
                        <li>El archivo debe estar codificado en UTF-8 para evitar problemas con caracteres especiales.</li>
                        <li>Las líneas con datos incompletos o inválidos serán omitidas.</li>
                        <li>Se recomienda hacer un respaldo antes de importaciones masivas.</li>
                        <li>Puede exportar el CSV actual como plantilla usando el botón "Descargar Plantilla".</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('csv_file');
    
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Por favor, seleccione un archivo CSV');
        return false;
    }
    
    const fileName = fileInput.files[0].name;
    if (!fileName.toLowerCase().endsWith('.csv')) {
        e.preventDefault();
        alert('Por favor, seleccione un archivo con extensión .csv');
        return false;
    }
    
    if (!confirm('¿Está seguro de importar este archivo? Los registros duplicados serán omitidos.')) {
        e.preventDefault();
        return false;
    }
});
</script>
